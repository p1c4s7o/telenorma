<?php

if(!defined('__ROOT__')) die('Unexpected root path');
if(!file_exists(__ROOT__) || !is_dir(__ROOT__)) die('Root path not found');

require_once __ROOT__ . 'helpers/funcs.php';
require_once __ROOT__ . 'exception/MethodNotAllowed.php';
require_once __ROOT__ . 'exception/Forbidden.php';
require_once __ROOT__ . 'exception/RouteNotFound.php';
require_once __ROOT__ . 'app/View.php';
require_once __ROOT__ . 'app/Route.php';
require_once __ROOT__ . 'app/Router.php';
require_once __ROOT__ . 'app/Config.php';
require_once __ROOT__ . 'app/Db.php';
require_once __ROOT__ . 'app/Action/User.php';
require_once __ROOT__ . 'app/Boot.php';
