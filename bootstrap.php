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
use Overtrue\Validation\Translator;
use Overtrue\Validation\Factory as ValidatorFactory;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

define('BASE_PATH', __DIR__);

require_once BASE_PATH . '/vendor/autoload.php';


$config = require BASE_PATH.'/config/app.php';
define('BASE_URL', $config['url']);
date_default_timezone_set($config['timezone']);


$database = require BASE_PATH.'/config/database.php';
$db = new DB;
$db->addConnection($database);
$db->setAsGlobal();
$db->bootEloquent();

define('LOG_PATH', BASE_PATH.'/resources/log');
if (!is_dir(LOG_PATH)) {
    mkdir(LOG_PATH, 0700, true);
}
$monolog = new \Monolog\Logger('system');
$monolog->pushHandler(new \Monolog\Handler\StreamHandler(LOG_PATH.'/app.log', \Monolog\Logger::ERROR));

$app = new \Slim\Slim([
//    'debug'          => $config->get('app.debug')
]);
//$app->config    = $config;
$app->validator = new ValidatorFactory(new Translator);

require BASE_PATH.'/config/route.php';

return $app;