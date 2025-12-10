<?php

if(!array_key_exists('REQUEST_URI', $_SERVER))
    exit("This script must be run from a web server");

if (!defined('__ROOT__'))
    define('__ROOT__', rtrim(dirname(__FILE__), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);

require_once __ROOT__ . 'boot/boot.php';

\App\Boot::$route_path = __ROOT__ . 'routes' . DIRECTORY_SEPARATOR;
\App\Boot::$route_ns = '\\Routes\\';
\App\Boot::$config_path = __ROOT__ . 'config' . DIRECTORY_SEPARATOR;

$boot = \App\Boot::init();
$boot->listen();

exit;
