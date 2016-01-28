<?php
/*
|--------------------------------------------------------------------------
| (APP)应用引导文件
|--------------------------------------------------------------------------
|
| 目前只支持MYSQL,未来可能会增加对NOSQL的支持
|
*/

require_once __DIR__.'/../vendor/autoload.php';


/*
|--------------------------------------------------------------------------
| 加载 Application Routes（路由）
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->group(['namespace' => 'App\Http\Controllers'], function ($app) {
    require __DIR__.'/../app/Config/route.php';
});

return $app;