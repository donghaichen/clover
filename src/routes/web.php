<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Clover the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', ['before' => 'Authenticate'], function() {
    echo 'Hello World!';
});
$router->group('admin', ['before' => 'Authenticate'], function($r)
{
    $r->get('foo', function(){ return ['page: admin/foo']; });
    $r->get('bar', function(){ echo 'page: admin/bar'; });
    $r->get('baz/php', function(){ echo 'page: admin/baz/php'; });
    $r->post('login', function(){ echo 'page: admin/login'; });
});

$router->get('/controller', 'ExampleController@test');