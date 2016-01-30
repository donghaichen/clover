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

    'sms' => [
        'url'      => env('SMS_URL',  'send_url'),
        'user'     => env('SMS_USER', 'username'),
        'pass'     => env('SMS_PASS', 'password'),
        'sign'     => env('SMS_SIGN', 'siginname'),
    ],
];
