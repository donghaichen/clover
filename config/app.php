<?php
// +----------------------------------------------------------------------
// | APP 设置
// +----------------------------------------------------------------------

return [
    // APP 名称
    'name'                   => getenv('APP_NAME'),
    // APP 环境
    'env'                    => getenv('APP_ENV'),
    // APP DEBUG
    'debug'                  => getenv('APP_DEBUG'),
    // APP
    'url'                    => getenv('APP_URL'),
    // APP 静态资源 URI
    'asset_url'              => null,
    // APP 时区
    'timezone'               => 'Asia/Shanghai',
    // APP 语言
    'locale'                 => 'zh-cn',
    // APP KEY
    'key'                    => getenv('APP_KEY'),
];
