<?php
// +----------------------------------------------------------------------
// | ROUTE 设置
// +----------------------------------------------------------------------

use App\Framework\Router;

Router::get('/', function (){
    echo 'Hello Clover';
});
Router::get('v1/database/dictionary', 'App\Controllers\DatabaseController@dictionary');
Router::get('v1/thumb', 'App\Controllers\ThumbController@show');

Router::get('v1/bing/home', 'App\Controllers\BingController@home');
Router::get('v1/bing/all', 'App\Controllers\BingController@all');
Router::get('v1/bing/options', 'App\Controllers\BingController@options');
Router::get('v1/bing/view/(:num)', 'App\Controllers\BingController@view');
Router::get('v1/bing/bg', 'App\Controllers\BingController@bg');
Router::get('v1/bing/download', 'App\Controllers\BingController@download');
Router::get('v1/bing/today', 'App\Controllers\BingController@today');
Router::get('v1/bing/image', 'App\Controllers\BingController@image');
Router::get('v1/bing/rand', 'App\Controllers\BingController@rand');
Router::get('v1/bing/imageDesc', 'App\Controllers\BingController@imageDesc');
Router::get('v1/bing/list', 'App\Controllers\BingController@list');
Router::get('v1/bing/getBing', 'App\Controllers\BingController@getBing');

Router::$error_callback = function() {
    throw new Exception("路由无匹配项 404 Not Found");
};

Router::dispatch();
