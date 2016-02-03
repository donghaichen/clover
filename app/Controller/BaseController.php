<?php
/*
|--------------------------------------------------------------------------
| CONTROLLER(控制器)基础文件，其他控制器必须继承该控制器
|--------------------------------------------------------------------------
|
| 该控制器主要用于一些项目的基本和重复的组件加载
|
*/

namespace Illuminate\Contracts\Database;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


/**
 * 基础控制器
 */
abstract class BaseController
{
    /**
     * 输出对象
     *
     * @var \Slim\Http\Request
     */
    protected $request;
    /**
     * 输出对象
     *
     * @var \Slim\Http\Response
     */
    protected $reponse;
    /**
     * Slim容器对象
     *
     * @var \Slim\Slim
     */
    public static $app;
    /**
     * constructor
     */
    final function __construct()
    {
        self::$app || self::$app = \Slim\Slim::getInstance();
        $this->request   = self::$app->request();
        $this->response  = self::$app->response();
        $this->config    = self::$app->config;
        $this->validator = self::$app->validator;
        $this->init();
    }
    /**
     * 初始调用方法
     *
     * @return void
     */
    public function init(){}
    /**
     * 输出模板
     *
     * @return Slim\View
     */
    public function view()
    {
        return call_user_func_array('view', func_get_args());
    }
    /**
     * 创建验证对象
     *
     * @param array $input
     * @param array $rules
     *
     * @return Validator
     */
    public function validate($input, $rules)
    {
        return $this->validator->make($input, $rules);
    }
    /**
     * 跳转
     *
     * @return Slim\Http\Response
     */
    public function redirect()
    {
        return call_user_func_array(array($this->response, 'redirect'), func_get_args());
    }
}