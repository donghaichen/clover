<?php
/**
 * 应用配置项
 * User: donghai
 * Date: 16/1/28
 * Time: 21:38
 */
return [

    'sms' => [
        'url'      => env('SMS_URL',  'send_url'),
        'user'     => env('SMS_USER', 'username'),
        'pass'     => env('SMS_PASS', 'password'),
        'sign'     => env('SMS_SIGN', 'siginname'),
    ],

];
