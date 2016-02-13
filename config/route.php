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
use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;
$collector = new RouteCollector();
$collector->get('/', function(){
    echo 'Home Page';
});
$collector->post('products', function(){
    return 'Create Product';
});
$collector->put('items/{id}', function($id){
    return 'Amend Item ' . $id;
});
$dispatcher =  new Dispatcher($collector->getData());
return $dispatcher;
//echo $dispatcher->dispatch('GET', '/'), "\n";   // Home Page
//echo $dispatcher->dispatch('POST', '/products'), "\n"; // Create Product
//echo $dispatcher->dispatch('PUT', '/items/123'), "\n"; // Amend Item 123