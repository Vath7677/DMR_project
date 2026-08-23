<?php
// backend/src/AuthController.php

class AuthController {
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

            echo json_encode(['status' => 'success', 'message' => 'Login successful!', 'role' => $user->role, 'username' => $user->username]);
        }
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