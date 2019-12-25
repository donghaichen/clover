<?php

/**
 * Clover - 为速度而生的 PHP 框架
 *
 * @package  Clover
 * @author   Donghai Chen <chendonghai888@gmail.com>
 */


/*
 * Register The Auto Loader
 */
require __DIR__ . '/../vendor/autoload.php';

/*
 *  Run The Application
 */
require __DIR__ . '/../app.php';
$app = new app();
$app->run();
