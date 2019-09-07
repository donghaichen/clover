<?php
// +----------------------------------------------------------------------
// | LOG 设置
// +----------------------------------------------------------------------
return [
    // 日志记录驱动
    'type'              => getenv('LOG_DRIVER'),
    // 关闭全局日志写入
    'close'        => false,
    // 日志路径
    'path'           => APP_PATH . DIRECTORY_SEPARATOR . getenv('LOG_PATH') . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR  . date('Y') . DIRECTORY_SEPARATOR . date('m'),
    // 单文件日志写入
    'single'         => false,
    // 最大日志文件数量
    'max_files'      => 0,
    // 使用JSON格式记录
    'json'           => false,
    // 日志处理
    'processor'      => null,
    // 日志时间格式化
    'date_format'         => 'Y-m-d H:i:s',
    // 是否实时写入
    'realtime_write' => false,
];
