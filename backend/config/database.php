<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;


require_once __DIR__ . '/../vendor/autoload.php';

$capsule = new Capsule;

$connectionConfig = [
    'driver'    => 'mysql',
    'host'      => getenv('DB_HOST') ?: 'db',
    'port'      => getenv('DB_PORT') ?: 3306,
    'database'  => getenv('DB_DATABASE') ?: 'dmr_db',
    'username'  => getenv('DB_USERNAME') ?: 'root',
    'password'  => getenv('DB_PASSWORD') ?: '2408',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
];

if (getenv('DB_HOST') && getenv('DB_HOST') !== 'db' && getenv('DB_HOST') !== 'localhost') {
    $connectionConfig['options'] = [
        PDO::MYSQL_ATTR_SSL_CA => true,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
    ];
} 

/* $connectionConfig = [
    'driver'    => 'mysql',
    'host'      => getenv('DB_HOST') ?: 'gateway01.ap-southeast-1.prod.aws.tidbcloud.com',
    'port'      => (int)(getenv('DB_PORT') ?: 4000),
    'database'  => getenv('DB_DATABASE') ?: 'test',
    'username'  => getenv('DB_USERNAME') ?: '3yZCToBnc7fvRe4.root',
    'password'  => getenv('DB_PASSWORD') ?: 'RxLYR71PaBc0nUGv',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
    'options'   => [
        (defined('Pdo\\Mysql::ATTR_SSL_CA') ? \Pdo\Mysql::ATTR_SSL_CA : PDO::MYSQL_ATTR_SSL_CA) => true,
        (defined('Pdo\\Mysql::ATTR_SSL_VERIFY_SERVER_CERT') ? \Pdo\Mysql::ATTR_SSL_VERIFY_SERVER_CERT : PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT) => false,
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ],
]; */

$capsule->addConnection($connectionConfig);

$capsule->setEventDispatcher(new Dispatcher(new Container));
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Guarantee avatar column exists in users table
try {
    Capsule::statement("ALTER TABLE `users` ADD `avatar` VARCHAR(255) NULL AFTER `role`;");
} catch (\Exception $e) {
    // Already exists
}