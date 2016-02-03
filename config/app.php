<?php
/*
|--------------------------------------------------------------------------
| APP(应用)常用参数配置配配置文件
|--------------------------------------------------------------------------
|
| 在这里定义您的应用配置
|
*/

return [
    'key'        => '12344555',
    'debug'      => 'true',
    'url'        => 'http://localhost',
    'timezone'   => 'PRC',
    'settings' => [
        'displayErrorDetails' => true,
        'logger' => [
            'name' => 'clover',
            'path' => __DIR__ . '/../resources/log/app.log',
        ],
    ],
];
