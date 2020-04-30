<?php

require_once __DIR__.'/../../vendor/autoload.php';

date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Clover\Application([
    'namespaces' => [
        'controllers' => 'App\Http\Controllers',
        'middlewares' => 'App\Http\Middlewares',
    ],
]);


// $app->withFacades();

// $app->withEloquent();


/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/
$router = $app->router;
require __DIR__.'/../routes/web.php';

/*
|--------------------------------------------------------------------------
| whoops 错误提示
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/
if (env('APP_DEBUG', 'false'))
{
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

return $app;
