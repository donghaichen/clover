<?php

namespace Clover;

use Clover\Response\Response;
use Clover\Routing\Router;
use Clover\Container\Container;


class Application extends Container
{

    /**
     * The application namespace.
     *
     * @var string
     */
    protected $namespace;

    /**
     * The Router instance.
     *
     * @var Clover\Routing\Router
     */
    public $router;

    /**
     * Create a new Clover application instance.
     *
     * @param  string|null  $basePath
     * @return void
     */
    public function __construct(array $attributes)
    {
        $this->bootstrapRouter($attributes);
    }

    /**
     * Bootstrap the router instance.
     *
     * @return void
     */
    public function bootstrapRouter($attributes)
    {
        $this->router = new Router($attributes);
    }

    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version()
    {
        return 'Clover (3.0.0-dev)';
    }

    /**
     * 执行应用程序
     * @access public
     * @param Request|null $request
     * @return Response
     */
    public function run($request = null)
    {
        //自动创建request对象
        $this->router->run();
        $response = new Response();
        $response->send();
    }

    /**
     * Report the exception to the exception handler.
     *
     * @param Throwable $e
     * @return void
     */
    protected function reportException(Throwable $e)
    {
        $this->app->make(Handle::class)->report($e);
    }

    /**
     * 执行应用程序
     * @param Request $request
     * @return mixed
     */
    protected function runWithRequest(Request $request)
    {
        $this->initialize();

        // 加载全局中间件
        $this->loadMiddleware();

        // 设置开启事件机制
        $this->app->event->withEvent($this->app->config->get('app.with_event', true));

        // 监听HttpRun
        $this->app->event->trigger(HttpRun::class);

        return $this->app->middleware->pipeline()
            ->send($request)
            ->then(function ($request) {
                return $this->dispatchToRoute($request);
            });
    }

    /**
     * Render the exception to a response.
     *
     * @param Request   $request
     * @param Throwable $e
     * @return Response
     */
    protected function renderException($request, Throwable $e)
    {
        return $this->app->make(Handle::class)->render($request, $e);
    }

}
