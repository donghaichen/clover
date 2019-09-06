<?php
namespace App\Controllers;

/**
 * BaseController
 */
class BaseController
{
    public $config;

    /*
    * 构造方法
    * 多用于初始化任务（比如对变量进行初始化赋值）
    */
    public function __construct()
    {
        global $config;
        $this->config = $config;
    }
}
