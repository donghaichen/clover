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


define('BASE_PATH', __DIR__);

require_once BASE_PATH . '/vendor/autoload.php';


$config = require BASE_PATH . '/config/app.php';
$settings= $config['settings'];
define('BASE_URL', $config['url']);
date_default_timezone_set($config['timezone']);


$database = require BASE_PATH.'/config/database.php';
$db = new DB;
$db->addConnection($database);
$db->setAsGlobal();
$db->bootEloquent();


$app = new \Slim\App($config);
//var_dump($settings);
//exit();
$container = $app->getContainer();
$container['logger'] = function () {
    $settings = $settings['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};


$app->validator = new ValidatorFactory(new Translator);

require BASE_PATH.'/config/route.php';

return $app;

