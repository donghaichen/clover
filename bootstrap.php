<?php

/*
|--------------------------------------------------------------------------
|(APP)应用引导文件
|--------------------------------------------------------------------------
|
| 目前只支持MYSQL,未来可能会增加对NOSQL的支持
|
*/
use Illuminate\Database\Capsule\Manager as DB;

define('BASE_PATH', __DIR__);

require_once BASE_PATH . '/vendor/autoload.php';


$db = new DB;
$db->addConnection($database);
$db->setAsGlobal();
$db->bootEloquent();

// BASE_URL
$config = require BASE_PATH.'/config/config.php';
define('BASE_URL', $config['base_url']);
// TIME_ZONE
date_default_timezone_set($config['time_zone']);
// Eloquent ORM
$capsule = new Capsule;
$capsule->addConnection(require BASE_PATH.'/config/database.php');
$capsule->bootEloquent();
// View Loader
class_alias('\TinyLara\View\View','View');

return $app;

