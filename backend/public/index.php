<?php

$origin = $_SERVER['HTTP_ORIGIN'] ?? 'http://localhost:5175';

header("Access-Control-Allow-Origin: $origin");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header('Content-Type: application/json');
session_start();

$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';

// call to ORM Database
require_once __DIR__ . '/../config/database.php';

// call to AuthController.php file
require_once __DIR__ . '/../src/AuthController.php';

// make an obj
$authController = new AuthController();

if ($url === 'api/auth/login') {
    $authController->login();
} else if ($url === 'api/auth/logout') {
    $authController->logout();
} else {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "Endpoint Not Found!"]);
}