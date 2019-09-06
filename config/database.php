<?php
return [
    // 数据库类型
    'type'              => getenv('DB_TYPE'),
    // 服务器地址
    'host'          => getenv('DB_HOST'),
    // 数据库名
    'database'          => getenv('DB_DATABASE'),
    // 用户名
    'username'          => getenv('DB_USERNAME'),
    // 密码
    'password'          => getenv('DB_PASSWORD'),
    // 端口
    'port'          => getenv('DB_PORT'),
    // 数据库连接参数
    'params'            => [],
    // 数据库编码默认采用utf8
    'charset'           => getenv('DB_CHARSET'),
    // 数据库表前缀
    'prefix'            => getenv('DB_PREFIX'),
    // 数据库调试模式
    'debug'             => getenv('DB_DEBUG'),

];
//return [
//
//    'driver' => 'mysql',
//
//    'host' => 'localhost',
//
//    'database' => getenv('DB_DATABASE'),
//
//    'username' => getenv('DB_USERNAME'),
//
//    'password' => getenv('DB_PASSWORD'),
//
//    'charset' => 'utf8mb4',
//
//    'collation' => 'utf8mb4_unicode_ci',
//
//    'prefix' => getenv('DB_PREFIX'),
//];
