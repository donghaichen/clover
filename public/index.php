<?php

/**
 * Clover - 为速度而生的 PHP 框架
 *
 * @package  Clover
 * @author   Donghai Chen <chendonghai888@gmail.com>
 */

//定义常量
define('CLOVER_START', microtime(true));
define('APP_VERSION', '2.0.0-dev');
define('APP_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);

/*
 * Register The Auto Loader
 */
require __DIR__.'/../vendor/autoload.php';

/*
 *  Run The Application
 */
require_once __DIR__.'/../app.php';
$app = new app();
$app->run();
