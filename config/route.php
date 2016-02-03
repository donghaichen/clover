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


$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});