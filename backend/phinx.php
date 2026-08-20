<?php

$dbHost = getenv('DB_HOST') ?: 'db';
$dbName = getenv('DB_DATABASE') ?: 'dmr_db';
$dbUser = getenv('DB_USERNAME') ?: 'root';
$dbPass = getenv('DB_PASSWORD') ?: '2408';
$dbPort = getenv('DB_PORT') ?: '3306';

return
    [
        'paths' => [
            'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
            'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
        ],
        'environments' => [
            'default_migration_table' => 'phinxlog',
            'default_environment' => 'development',

            'development' => [
                'adapter' => 'mysql',
                'host' => $dbHost,
                'name' => $dbName,
                'user' => $dbUser,
                'pass' => $dbPass,
                'port' => $dbPort,
                'charset' => 'utf8',
            ],
            'production' => [
                'adapter' => 'mysql',
                'host' => $dbHost,
                'name' => $dbName,
                'user' => $dbUser,
                'pass' => $dbPass,
                'port' => $dbPort,
                'charset' => 'utf8',
                'mysql_attr_ssl_verify_server_cert' => false,
            ]
        ],
        'version_order' => 'creation'
    ];
