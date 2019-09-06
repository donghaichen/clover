<?php

use NoahBuscher\Macaw\Macaw;
Macaw::get('v1/database/dictionary', 'App\Controllers\DatabaseController@dictionary');

Macaw::get('v1/bing/home', 'App\Controllers\BingController@home');
Macaw::get('v1/bing/all', 'App\Controllers\BingController@all');
Macaw::get('v1/bing/options', 'App\Controllers\BingController@options');
Macaw::get('v1/bing/view/(:num)', 'App\Controllers\BingController@view');
Macaw::get('v1/bing/bg', 'App\Controllers\BingController@bg');
Macaw::get('v1/bing/download', 'App\Controllers\BingController@download');
Macaw::get('v1/bing/today', 'App\Controllers\BingController@today');
Macaw::get('v1/bing/image', 'App\Controllers\BingController@image');
Macaw::get('v1/bing/rand', 'App\Controllers\BingController@rand');
Macaw::get('v1/bing/imageDesc', 'App\Controllers\BingController@imageDesc');
Macaw::get('v1/bing/list', 'App\Controllers\BingController@list');
Macaw::get('v1/bing/getBing', 'App\Controllers\BingController@getBing');

Macaw::$error_callback = function() {
    throw new Exception("路由无匹配项 404 Not Found");
};

Macaw::dispatch();
