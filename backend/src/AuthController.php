<?php
// backend/src/AuthController.php

use Illuminate\Database\Capsule\Manager as Capsule;

class AuthController {

    public function __construct() {
        // Auto-ensure avatar column exists in users table
        try {
            if (!Capsule::schema()->hasColumn('users', 'avatar')) {
                Capsule::schema()->table('users', function ($table) {
                    $table->string('avatar', 255)->nullable()->after('role');
                });
            }
        } catch (\Exception $e) {
            // Ignore if already exists or permission
        }
    }

    // Login
    public function login() {
        $max_attempts = 5;
        $lockout_time = 60;

        if (isset($_SESSION['locked_until']) && $_SESSION['locked_until'] > time()) {
            $remaining_time = $_SESSION['locked_until'] - time();
            echo json_encode(['status' => 'locked', 'message' => "Too many failed attempts. Please wait $remaining_time seconds.", 'remaining_time' => $remaining_time]);
            return;
        }

        if (isset($_SESSION['locked_until']) && $_SESSION['locked_until'] <= time()) {
            unset($_SESSION['locked_until']);
            $_SESSION['failed_attempts'] = 0;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $data['email'] ?? '';
            $password = $data['password'] ?? '';

            if (!isset($_SESSION['failed_attempts'])) $_SESSION['failed_attempts'] = 0;

            // call the User Model
            require_once __DIR__ . '/User.php';
            
            // Find the user by email using Eloquent ORM
            $user = User::where('email', $email)->first();

            if (!$user || !password_verify($password, $user->password)) {
                $_SESSION['failed_attempts']++;

                if ($_SESSION['failed_attempts'] >= $max_attempts) {
                    $_SESSION['locked_until'] = time() + $lockout_time;
                    echo json_encode([
                        'status' => 'locked', 
                        'message' => "Too many failed attempts. Account locked for {$lockout_time} seconds.",
                        'remaining_time' => $lockout_time
                    ]);
                    return;
                }

                $attempts_left = $max_attempts - $_SESSION['failed_attempts'];
                echo json_encode([
                    'status' => 'error', 
                    'message' => "Invalid email or password! You have {$attempts_left} attempt" . ($attempts_left > 1 ? 's' : '') . " remaining.",
                    'attempts_left' => $attempts_left
                ]);
                return;
            }

            $_SESSION['failed_attempts'] = 0;
            unset($_SESSION['locked_until']);
            session_regenerate_id(true);

            // 🔑 SET THE SESSION VARIABLES
            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->username;
            $_SESSION['role'] = $user->role;

            echo json_encode([
                'status' => 'success', 
                'message' => 'Login successful!', 
                'role' => $user->role, 
                'username' => $user->username,
                'email' => $user->email,
                'avatar' => $user->avatar ?: null
            ]);
        }
    }

    // Helper to find authenticated user
    private function resolveUser($data = []) {
        require_once __DIR__ . '/User.php';
        $userId = $_SESSION['user_id'] ?? null;
        if ($userId) {
            $user = User::find($userId);
            if ($user) return $user;
        }

        $email = $data['email'] ?? ($_POST['email'] ?? null);
        if ($email) {
            $user = User::where('email', $email)->first();
            if ($user) return $user;
        }

        // Fallback to first user in database
        return User::first();
    }

    // Get current user profile
    public function getProfile() {
        $user = $this->resolveUser();
        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
            return;
        }

        echo json_encode([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'avatar' => $user->avatar ?: null
            ]
        ]);
    }

    // Update Profile Information (name, email)
    public function updateProfile() {
        $data = json_decode(file_get_contents('php://input'), true) ?: [];
        $user = $this->resolveUser($data);
        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
            return;
        }

        if (!empty($data['username'])) {
            $user->username = strip_tags(trim($data['username']));
            $_SESSION['username'] = $user->username;
        }

        if (!empty($data['email'])) {
            $newEmail = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
            $existing = User::where('email', $newEmail)->where('id', '!=', $user->id)->first();
            if ($existing) {
                echo json_encode(['status' => 'error', 'message' => 'Email is already in use by another account.']);
                return;
            }
            $user->email = $newEmail;
            $_SESSION['email'] = $newEmail;
        }

        $user->save();

        echo json_encode([
            'status' => 'success',
            'message' => 'Profile updated successfully!',
            'username' => $user->username,
            'email' => $user->email,
            'avatar' => $user->avatar ?: null
        ]);
    }

    // Upload & Save Avatar Image to backend/public/uploads/
    public function uploadAvatar() {
        $user = $this->resolveUser();
        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
            return;
        }

        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['status' => 'error', 'message' => 'No file uploaded or upload error.']);
            return;
        }

        $file = $_FILES['avatar'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($ext, $allowed)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid file format. Only JPG, PNG, GIF, and WebP are allowed.']);
            return;
        }

        // 800KB max limit check
        if ($file['size'] > 800 * 1024) {
            echo json_encode(['status' => 'error', 'message' => 'File size exceeds maximum limit of 800KB.']);
            return;
        }

        $uploadDir = __DIR__ . '/../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Delete old avatar if exists
        if (!empty($user->avatar)) {
            $oldPath = __DIR__ . '/../public/' . ltrim($user->avatar, '/');
            if (file_exists($oldPath) && is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        $newFilename = 'avatar_' . $user->id . '_' . time() . '.' . $ext;
        $targetPath = $uploadDir . $newFilename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $relativePath = 'uploads/' . $newFilename;
            $user->avatar = $relativePath;
            $user->save();

            echo json_encode([
                'status' => 'success',
                'message' => 'Profile picture updated successfully!',
                'avatar' => $relativePath
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save uploaded image.']);
        }
    }

    // Delete / Remove Avatar (reset to default)
    public function deleteAvatar() {
        $user = $this->resolveUser();
        if ($user && !empty($user->avatar)) {
            $filePath = __DIR__ . '/../public/' . ltrim($user->avatar, '/');
            if (file_exists($filePath) && is_file($filePath)) {
                @unlink($filePath);
            }
            $user->avatar = null;
            $user->save();
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Profile picture removed successfully!'
        ]);
    }

    // Update password
    public function updatePassword() {
        require_once __DIR__ . '/User.php';
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $currentPassword = $data['currentPassword'] ?? '';
        $newPassword = $data['newPassword'] ?? '';

        $user = User::find($userId);
        if (!$user || !password_verify($currentPassword, $user->password)) {
            echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect.']);
            return;
        }

        if (strlen($newPassword) < 6) {
            echo json_encode(['status' => 'error', 'message' => 'New password must be at least 6 characters.']);
            return;
        }

        $user->password = password_hash($newPassword, PASSWORD_DEFAULT);
        $user->save();

        echo json_encode(['status' => 'success', 'message' => 'Password updated successfully!']);
    }

    // logout
    public function logout() {
        $_SESSION = [];
        if (session_id()) {
            session_destroy();
        }
        echo json_encode(['status' => 'success', 'message' => 'Logout successful!']);
    }
}