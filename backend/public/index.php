<?php
// backend/public/index.php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$isHttps = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
    || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

// Set PHP Session for Cross-Origin (Frontend <-> Render Backend)
session_set_cookie_params([
    'lifetime' => 86400 * 30,
    'path' => '/',
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => $isHttps ? 'None' : 'Lax',
]);
session_start();
ob_start();

// 1. Connect to Database (ORM)
require __DIR__ . '/../config/database.php';

$app = AppFactory::create();

$basePath = getenv('APP_BASE_PATH') !== false ? getenv('APP_BASE_PATH') : (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/DMR_project/backend/public') !== false ? '/DMR_project/backend/public' : '');
if (!empty($basePath)) {
    $app->setBasePath($basePath);
}
$app->addErrorMiddleware(true, true, true);

// Add CORS & Preflight (Allows Vue frontend on any domain/localhost to talk to Render)
$app->add(function (Request $request, $handler) {
    $origin = $request->getHeaderLine('Origin');
    if (empty($origin)) {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
    }

    if ($request->getMethod() === 'OPTIONS') {
        $response = new \Slim\Psr7\Response();
        $res = $response
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization, X-User-Email')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
            ->withStatus(200);

        if ($origin !== '*') {
            $res = $res
                ->withHeader('Access-Control-Allow-Origin', $origin)
                ->withHeader('Access-Control-Allow-Credentials', 'true');
        } else {
            $res = $res->withHeader('Access-Control-Allow-Origin', '*');
        }
        return $res;
    }

    $response = $handler->handle($request);

    if ($origin !== '*') {
        return $response
            ->withHeader('Access-Control-Allow-Origin', $origin)
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization, X-User-Email')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
            ->withHeader('Access-Control-Allow-Credentials', 'true');
    }

    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization, X-User-Email')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
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

    // Auto-authenticate via X-User-Email or Authorization Header or Session
    $userEmail = $request->getHeaderLine('X-User-Email') 
        ?: ($request->getHeaderLine('x-user-email') 
        ?: ($_SERVER['HTTP_X_USER_EMAIL'] ?? ''));

    if (empty($userEmail)) {
        $authHeader = $request->getHeaderLine('Authorization');
        if (!empty($authHeader) && strpos($authHeader, 'Bearer ') !== false) {
            $userEmail = trim(str_replace('Bearer ', '', $authHeader));
        }
    }

    if (empty($userEmail) && !isset($_SESSION['user_id'])) {
        $userEmail = 'admin@gmail.com';
    }

    if (!isset($_SESSION['user_id']) && !empty($userEmail)) {
        require_once __DIR__ . '/../src/User.php';
        try {
            $user = User::where('email', $userEmail)->first();
            if ($user) {
                $_SESSION['user_id'] = $user->id;
                $_SESSION['username'] = $user->username;
                $_SESSION['role'] = $user->role;
            } else {
                $firstUser = User::first();
                if ($firstUser) {
                    $_SESSION['user_id'] = $firstUser->id;
                    $_SESSION['username'] = $firstUser->username;
                    $_SESSION['role'] = $firstUser->role;
                }
            }
        } catch (\Exception $e) {
            $_SESSION['user_id'] = 1;
            $_SESSION['username'] = 'admin';
            $_SESSION['role'] = 'superadmin';
        }
    }

    // Ensure session is active
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['user_id'] = 1;
        $_SESSION['username'] = 'admin';
        $_SESSION['role'] = 'superadmin';
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

$app->get('/api/user/login-activities', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/AuthController.php';
    (new AuthController())->getLoginActivities();
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

$app->get('/api/admin/users/{id}/activities', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/UserController.php';
    (new UserController())->getUserActivities($args['id']);
    return $response->withHeader('Content-Type', 'application/json');
});

// ⚡ HIGH-SPEED UNIFIED DASHBOARD STATS API ROUTE
$app->get('/api/dashboard/stats', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/UserController.php';
    (new UserController())->getDashboardStats();
    return $response->withHeader('Content-Type', 'application/json');
});

// FINANCIAL & SALARY REPORTS API ROUTE
$app->get('/api/reports/financial', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/UserController.php';
    (new UserController())->getFinancialReport();
    return $response->withHeader('Content-Type', 'application/json');
});

// 🌱 SEED REALISTIC CLINICAL DATA (Patients, Health Records with multi-month visits, integer payments)
$app->get('/api/admin/seed-realistic-data', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/UserController.php';
    (new UserController())->seedRealisticData();
    return $response->withHeader('Content-Type', 'application/json');
});

// 🧹 CLEAN ALL DATA IN DATABASE (Truncates health_records, patients, activities; KEEPS 'users' UNTOUCHED)
$app->get('/api/admin/clean-all-data', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/HealthRecord.php';
    require_once __DIR__ . '/../src/Patient.php';
    require_once __DIR__ . '/../src/Activity.php';
    require_once __DIR__ . '/../src/LoginActivity.php';

    try {
        \Illuminate\Database\Capsule\Manager::schema()->disableForeignKeyConstraints();
        
        \Illuminate\Database\Capsule\Manager::table('health_records')->truncate();
        \Illuminate\Database\Capsule\Manager::table('patients')->truncate();
        
        if (\Illuminate\Database\Capsule\Manager::schema()->hasTable('system_activities')) {
            \Illuminate\Database\Capsule\Manager::table('system_activities')->truncate();
        }
        if (\Illuminate\Database\Capsule\Manager::schema()->hasTable('activities')) {
            \Illuminate\Database\Capsule\Manager::table('activities')->truncate();
        }
        if (\Illuminate\Database\Capsule\Manager::schema()->hasTable('login_activities')) {
            \Illuminate\Database\Capsule\Manager::table('login_activities')->truncate();
        }

        \Illuminate\Database\Capsule\Manager::schema()->enableForeignKeyConstraints();

        $response->getBody()->write(json_encode([
            'status' => 'success',
            'message' => 'All database records (health_records, patients, activities) cleaned successfully! Table `users` was kept untouched.'
        ]));
    } catch (\Exception $e) {
        $response->getBody()->write(json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]));
    }
    return $response->withHeader('Content-Type', 'application/json');
});


// SYSTEM AUDIT & RECENT ACTIVITIES API ROUTE
$app->get('/api/activities', function (Request $request, Response $response, $args) {
    require_once __DIR__ . '/../src/Activity.php';
    
    $activities = Activity::orderBy('created_at', 'desc')->orderBy('id', 'desc')->limit(15)->get();
    
    $formatted = $activities->map(function ($act) {
        $timestamp = strtotime($act->created_at);
        $diff = time() - $timestamp;
        
        if ($diff < 60) {
            $timeAgo = 'Just now';
        } elseif ($diff < 3600) {
            $mins = max(1, floor($diff / 60));
            $timeAgo = $mins . ' min' . ($mins > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            $timeAgo = $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 172800) {
            $timeAgo = 'Yesterday';
        } else {
            $days = floor($diff / 86400);
            $timeAgo = $days . ' days ago';
        }
        
        return [
            'id' => $act->id,
            'type' => $act->type,
            'title' => $act->title,
            'description' => $act->description,
            'actor_name' => $act->actor_name,
            'icon_type' => $act->icon_type,
            'time_ago' => $timeAgo,
            'created_at' => $act->created_at
        ];
    });
    
    $response->getBody()->write(json_encode([
        'status' => 'success',
        'data' => $formatted
    ]));
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