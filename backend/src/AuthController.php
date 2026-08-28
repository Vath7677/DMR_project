<?php
// backend/src/AuthController.php

use Illuminate\Database\Capsule\Manager as Capsule;

class AuthController {

    public function __construct() {
        // Ready
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

            // 📝 Log Login Activity into database
            try {
                require_once __DIR__ . '/LoginActivity.php';
                $uaInfo = $this->parseUserAgent();
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? ($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');
                
                LoginActivity::create([
                    'user_id' => $user->id,
                    'device_name' => $uaInfo['device'],
                    'browser' => $uaInfo['browser'],
                    'ip_address' => $ip,
                    'session_id' => session_id(),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            } catch (\Exception $e) {
                // Silently continue
            }

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

    // Parse User Agent string to extract Device and Browser
    private function parseUserAgent() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $device = 'MacBook Pro';
        $browser = 'Chrome';

        // 1. Detect Device / OS
        if (preg_match('/iPhone/i', $userAgent)) {
            $device = 'iPhone 14';
        } elseif (preg_match('/iPad/i', $userAgent)) {
            $device = 'iPad Pro';
        } elseif (preg_match('/Android/i', $userAgent)) {
            $device = preg_match('/Mobile/i', $userAgent) ? 'Android Phone' : 'Android Tablet';
        } elseif (preg_match('/Macintosh|Mac OS X/i', $userAgent)) {
            $device = 'MacBook Pro';
        } elseif (preg_match('/Windows NT 10.0/i', $userAgent) || preg_match('/Windows NT 11.0/i', $userAgent)) {
            $device = 'Windows PC';
        } elseif (preg_match('/Linux/i', $userAgent)) {
            $device = 'Linux Workstation';
        }

        // 2. Detect Browser
        if (preg_match('/Edg/i', $userAgent)) {
            $browser = 'Edge';
        } elseif (preg_match('/Chrome/i', $userAgent) && !preg_match('/Edg|OPR/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Safari/i', $userAgent) && !preg_match('/Chrome|CriOS/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/Firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/OPR|Opera/i', $userAgent)) {
            $browser = 'Opera';
        }

        return ['device' => $device, 'browser' => $browser];
    }

    // Get Login Activities for current user
    public function getLoginActivities() {
        require_once __DIR__ . '/LoginActivity.php';
        $user = $this->resolveUser();
        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
            return;
        }

        $activities = LoginActivity::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        // If no activity yet, auto-create current session record
        if ($activities->isEmpty()) {
            $uaInfo = $this->parseUserAgent();
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? ($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');
            $act = LoginActivity::create([
                'user_id' => $user->id,
                'device_name' => $uaInfo['device'],
                'browser' => $uaInfo['browser'],
                'ip_address' => $ip,
                'session_id' => session_id(),
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $activities = collect([$act]);
        }

        $formatted = [];
        foreach ($activities as $index => $act) {
            $isCurrent = ($index === 0);
            
            $timeStr = 'Just now';
            if ($act->created_at) {
                $actTime = strtotime($act->created_at);
                $now = time();
                $diff = $now - $actTime;
                
                if ($isCurrent) {
                    $timeStr = 'Current Session';
                } elseif ($diff < 3600) {
                    $mins = max(1, round($diff / 60));
                    $timeStr = "{$mins}m ago";
                } elseif (date('Y-m-d', $actTime) === date('Y-m-d')) {
                    $timeStr = 'Today, ' . date('H:i', $actTime);
                } elseif (date('Y-m-d', $actTime) === date('Y-m-d', strtotime('-1 day'))) {
                    $timeStr = 'Yesterday, ' . date('H:i', $actTime);
                } else {
                    $timeStr = date('M d, H:i', $actTime);
                }
            }

            $isMobile = (stripos($act->device_name, 'phone') !== false || stripos($act->device_name, 'iPhone') !== false || stripos($act->device_name, 'Android') !== false);

            $formatted[] = [
                'id' => $act->id,
                'deviceName' => $act->device_name,
                'browser' => $act->browser,
                'ip' => $act->ip_address,
                'isCurrent' => $isCurrent,
                'timeStr' => $timeStr,
                'type' => $isMobile ? 'mobile' : 'desktop'
            ];
        }

        echo json_encode([
            'status' => 'success',
            'data' => $formatted
        ]);
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
            // Superadmin has full access to update email; other staff roles cannot change their email
            if ($user->role === 'superadmin') {
                $newEmail = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
                $existing = User::where('email', $newEmail)->where('id', '!=', $user->id)->first();
                if ($existing) {
                    echo json_encode(['status' => 'error', 'message' => 'Email is already in use by another account.']);
                    return;
                }
                $user->email = $newEmail;
                $_SESSION['email'] = $newEmail;
            }
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
        $data = json_decode(file_get_contents('php://input'), true) ?: [];
        $user = $this->resolveUser($data);
        
        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'User account not found.']);
            return;
        }

        $currentPassword = $data['currentPassword'] ?? '';
        $newPassword = $data['newPassword'] ?? '';

        if (empty($currentPassword)) {
            echo json_encode(['status' => 'error', 'message' => 'Please enter your current password.']);
            return;
        }

        if (!password_verify($currentPassword, $user->password)) {
            echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect! Please try again.']);
            return;
        }

        if (empty($newPassword)) {
            echo json_encode(['status' => 'error', 'message' => 'Please enter a new password.']);
            return;
        }

        if (strlen($newPassword) < 6) {
            echo json_encode(['status' => 'error', 'message' => 'New password must be at least 6 characters.']);
            return;
        }

        if ($currentPassword === $newPassword) {
            echo json_encode(['status' => 'error', 'message' => 'New password cannot be the same as your current password!']);
            return;
        }

        $user->password = password_hash($newPassword, PASSWORD_BCRYPT);
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