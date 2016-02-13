<?php
/*
|--------------------------------------------------------------------------
| ROUTES(路由)映射表
|--------------------------------------------------------------------------
|
| 目前想法是优先匹配配置文件，找不到配置文件自动GET请求自动匹配控制器方法，
| 例如/user 自动匹配UserController@index
|
*/
use TinyLara\Routing\Router as Route;
Route::get('/', 'HomeController@home');
Route::any('foo', function() {
    echo "Foo!";
});
Route::filter(function() {
    return isset($_GET['token']) && $_GET['token'] == 1;
}, function(){
    Route::any('bar', function() {
        echo "Bar!<br>Filter Success!";
    });
});
Route::dispatch();
