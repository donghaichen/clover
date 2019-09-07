<?php

/**
 * Clover - A PHP Framework
 *
 * @package  Clover
 * @author   Donghai Chen <chendonghai888@gmail.com>
 */

//定义常量
define('CLOVER_START', microtime(true));
define('APP_VERSION', '1.0.1');
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
