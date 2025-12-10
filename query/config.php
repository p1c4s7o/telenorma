<?php

return [
    'driver' => env('DB_DRIVER', 'mysql'),

    'db_name' => env('DB_NAME', 'tl_test'),

    'host' => env('DB_HOST', 'localhost'),
    'port' => env('DB_PORT', 3306),
    'user' => env('DB_USER', 'root'),
    'password' => env('DB_PSWD', ''),

    'attributes' => [
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
];
