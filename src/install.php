<?php

if (!defined('__ROOT__'))
    define('__ROOT__', rtrim(dirname(__FILE__), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);

require_once __ROOT__ . 'boot/boot.php';
require_once __ROOT__ . 'app/Migrations.php';

\App\Boot::$route_path = __ROOT__ . 'routes' . DIRECTORY_SEPARATOR;
\App\Boot::$route_ns = '\\Routes\\';
\App\Boot::$config_path = __ROOT__ . 'config' . DIRECTORY_SEPARATOR;
\App\Boot::$migration_path = __ROOT__ . 'migrations' . DIRECTORY_SEPARATOR;

$boot = \App\Boot::init();
$boot->run_migrations();

