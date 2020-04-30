<?php

/**
 * Clover - 为速度而生的 PHP 框架
 *
 * @package  Clover
 * @author   Donghai Chen <chendonghai888@gmail.com>
 */


define('PUBLIC_PATH', __DIR__);
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', dirname(__DIR__) . '/src');
define('STORAGE_PATH', APP_PATH . '/storage');

/*
 *  Run The Application
 */

$app = require __DIR__.'/../src/bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$app->run();
