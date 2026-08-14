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
            exit();
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

            if (!$user) {
                echo json_encode(['status' => 'error', 'message' => "Email not found!"]);
                exit();
            }
                        if ($user->password !== $password) {
                echo json_encode(['status' => 'error', 'message' => "Password mismatch!"]);
                exit();
            }

            $_SESSION['failed_attempts'] = 0;
            echo json_encode(['status' => 'success', 'message' => 'Login successful!', 'role' => $user->role, 'username' => $user->username]);
        }
    }

    // logout
    public function logout() {
        session_destroy();
        echo json_encode(['status' => 'success', 'message' => 'Logout successful!']);
    }
}