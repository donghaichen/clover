<?php
/*
|--------------------------------------------------------------------------
| DATABASES(数据库)配配置文件
|--------------------------------------------------------------------------
|
| 目前只支持MYSQL,未来可能会增加对NOSQL的支持
|
*/
return [
    'driver'    => 'mysql',
    'host'      => '127.0.0.1',
    'database'  => 'clover',
    'username'  => 'clover',
    'password'  => 'clover',
    'hostport'  => '3306',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => 'cl_',
];
