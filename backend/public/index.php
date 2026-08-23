<?php
// backend/public/index.php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Set PHP Session to 30 days to match Vue's localStorage
session_set_cookie_params([
    'lifetime' => 86400 * 30,
    'path' => '/',
    'samesite' => 'Lax'
]);
session_start();
ob_start();

// 1. Connect to Database (ORM)
require __DIR__ . '/../config/database.php';

$app = AppFactory::create();

$app->setBasePath('/DMR_project/backend/public');
$app->addErrorMiddleware(true, true, true);

// Add CORS (This allows your Vue frontend to talk to the backend)
$app->add(function (Request $request, $handler) {
    $response = $handler->handle($request);

    $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*';

    return $response
        ->withHeader('Access-Control-Allow-Origin', $origin)
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->withHeader('Access-Control-Allow-Credentials', 'true');
});

// Handle CORS Preflight requests for all routes
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

// 🛡️ GLOBAL AUTH MIDDLEWARE (The Iron Door)
$app->add(function (Request $request, $handler) {
    $path = $request->getUri()->getPath();
    
    // Ignore login, profile/avatar, admin user management, reports, and OPTIONS requests
    if (strpos($path, '/api/auth/login') !== false || 
        strpos($path, '/api/user/') !== false || 
        strpos($path, '/api/admin/') !== false || 
        strpos($path, '/api/reports/') !== false || 
        strpos($path, '/uploads/') !== false || 
        $request->getMethod() === 'OPTIONS') {
        return $handler->handle($request);
    }

    // Check for the "Key" (Session)
    if (!isset($_SESSION['user_id'])) {
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode([
            "status" => "error",
            "message" => "401 Unauthorized: Get out, Hacker!"
        ]));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }

    return $handler->handle($request);
});

// RESTFUL API ROUTES (Write everything in ONE file!)

// AUTH & USER PROFILE API ROUTES
$app->post('/api/auth/login', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/AuthController.php';
    (new AuthController())->login();
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/api/auth/logout', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/AuthController.php';
    (new AuthController())->logout();
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/api/user/profile', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/AuthController.php';
    (new AuthController())->getProfile();
    return $response->withHeader('Content-Type', 'application/json');
});

$app->put('/api/user/profile', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/AuthController.php';
    (new AuthController())->updateProfile();
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/api/user/avatar', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/AuthController.php';
    (new AuthController())->uploadAvatar();
    return $response->withHeader('Content-Type', 'application/json');
});

$app->delete('/api/user/avatar', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/AuthController.php';
    (new AuthController())->deleteAvatar();
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/api/user/password', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/AuthController.php';
    (new AuthController())->updatePassword();
    return $response->withHeader('Content-Type', 'application/json');
});

// Explicit Uploads File Serving Route (Guarantees image loads regardless of Apache root)
$app->get('/uploads/{filename:.+}', function (Request $request, Response $response, $args) {
    $filePath = __DIR__ . '/uploads/' . basename($args['filename']);
    if (file_exists($filePath) && is_file($filePath)) {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml'
        ];
        $contentType = $mimes[$ext] ?? 'application/octet-stream';
        $response->getBody()->write(file_get_contents($filePath));
        return $response->withHeader('Content-Type', $contentType)->withHeader('Access-Control-Allow-Origin', '*');
    }
    return $response->withStatus(404);
});

// SUPERADMIN USER MANAGEMENT API ROUTES
$app->get('/api/admin/users', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/UserController.php';
    (new UserController())->getAllUsers();
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/api/admin/users', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/UserController.php';
    (new UserController())->createUser();
    return $response->withHeader('Content-Type', 'application/json');
});

$app->put('/api/admin/users/{id}', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/UserController.php';
    (new UserController())->updateUser($args['id']);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->delete('/api/admin/users/{id}', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/UserController.php';
    (new UserController())->deleteUser($args['id']);
    return $response->withHeader('Content-Type', 'application/json');
});

// FINANCIAL & SALARY REPORTS API ROUTE
$app->get('/api/reports/financial', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/UserController.php';
    (new UserController())->getFinancialReport();
    return $response->withHeader('Content-Type', 'application/json');
});


// PATIENT API ROUTES
$app->get('/api/patients', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/PatientController.php';
    (new PatientController())->getAllPatients();
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/api/patients/{id}', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/PatientController.php';
    (new PatientController())->getPatientById($args['id']);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/api/patients', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/PatientController.php';
    (new PatientController())->createPatient();
    return $response->withHeader('Content-Type', 'application/json');
});

$app->put('/api/patients/{id}', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/PatientController.php';
    (new PatientController())->updatePatient($args['id']);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->delete('/api/patients/{id}', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/PatientController.php';
    (new PatientController())->deletePatient($args['id']);
    return $response->withHeader('Content-Type', 'application/json');
});


// --> HEALTH RECORDS API ROUTES

$app->get('/api/health-records', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/HealthRecordController.php';
    (new HealthRecordController())->getAllRecords();
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/api/health-records', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/HealthRecordController.php';
    (new HealthRecordController())->createRecord();
    return $response->withHeader('Content-Type', 'application/json');
});

$app->put('/api/health-records/{id}', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/HealthRecordController.php';
    (new HealthRecordController())->updateRecord($args['id']);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/api/health-records/{id}', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/HealthRecordController.php';
    (new HealthRecordController())->updateRecord($args['id']);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->delete('/api/health-records/{id}', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/HealthRecordController.php';
    (new HealthRecordController())->deleteRecord($args['id']);
    return $response->withHeader('Content-Type', 'application/json');
});

// Run the app
$app->run();