<?php
// backend/public/index.php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

session_start();

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

// RESTFUL API ROUTES (Write everything in ONE file!)

// Login API
$app->post('/api/auth/login', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/AuthController.php';
    (new AuthController())->login();
    return $response; // AuthController already echoes JSON, so we just return the response
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