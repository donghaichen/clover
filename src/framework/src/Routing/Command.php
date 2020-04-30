<?php
/**
 * @Package: Clover RoutingCommand
 * @Class  : Command
 * @Author : @donghaichen <chendongahai888@gmai.com>
 * @URL    : https://github.com/donghaichen
 * @URL    : https://github.com/izniburak/php-router
 */

namespace Clover\Routing;

class Command
{
    /**
     * @var RouterCommand|null Class instance variable
     */
    protected static $instance = null;

    protected $baseFolder;
    protected $paths;
    protected $namespaces;

    /**
     * RouterCommand constructor.
     *
     * @param $baseFolder
     * @param $paths
     * @param $namespaces
     */
    public function __construct($baseFolder, $paths, $namespaces)
    {
        $this->baseFolder = $baseFolder;
        $this->paths = $paths;
        $this->namespaces = $namespaces;
    }

    public function getMiddlewareInfo()
    {
        return [
            'path' => $this->baseFolder . '/' . $this->paths['middlewares'],
            'namespace' => $this->namespaces['middlewares'],
        ];
    }

    public function getControllerInfo()
    {
        return [
            'path' => $this->baseFolder . '/' . $this->paths['controllers'],
            'namespace' => $this->namespaces['controllers'],
        ];
    }

    /**
     * @param $baseFolder
     * @param $paths
     * @param $namespaces
     *
     * @return RouterCommand|static
     */
    public static function getInstance($baseFolder, $paths, $namespaces)
    {
        if (null === self::$instance) {
            self::$instance = new static($baseFolder, $paths, $namespaces);
        }
        return self::$instance;
    }

    /**
     * Throw new Exception for Router Error
     *
     * @param $message
     *
     * @return RouterException
     * @throws
     */
    public function exception($message = '')
    {
        return new RouterException($message);
    }

    /**
     * Run Route Middlewares
     *
     * @param $command
     *
     * @return mixed|void
     * @throws
     */
    public function beforeAfter($command)
    {
        if (! is_null($command)) {
            $info = $this->getMiddlewareInfo();
            if (is_array($command)) {
                foreach ($command as $value) {
                    $this->beforeAfter($value);
                }
            } elseif (is_string($command)) {
                $middleware = explode(':', $command);
                $params = [];
                if (count($middleware) > 1) {
                    $params = explode(',', $middleware[1]);
                }
                $controller = $this->resolveClass($middleware[0], $info['namespace']);
                if (method_exists($controller, 'handle')) {
                    $response = call_user_func_array([$controller, 'handle'], $params);
                    if ($response !== true) {
                        echo $response;
                        exit;
                    }

                    return $response;
                }

                return $this->exception('handle() method is not found in <b>'.$command.'</b> class.');
            }
        }

        return;
    }

    /**
     * Run Route Command; Controller or Closure
     *
     * @param $command
     * @param $params
     *
     * @return mixed|void
     * @throws
     */
    public function runRoute($command, $params = null)
    {
        $info = $this->getControllerInfo();
        if (! is_object($command)) {
            $segments = explode('@', $command);
            $controllerClass = str_replace([$info['namespace'], '\\', '.'], ['', '/', '/'], $segments[0]);
            $controllerMethod = $segments[1];

            $controller = $this->resolveClass($controllerClass, $info['namespace']);
            if (method_exists($controller, $controllerMethod)) {
                echo $this->runMethodWithParams([$controller, $controllerMethod], $params);
                return;
            }

            return $this->exception($controllerMethod . ' method is not found in '.$controllerClass.' class.');
        } else {
            echo $this->runMethodWithParams($command, $params);
        }

        return;
    }

    /**
     * Resolve Controller or Middleware class.
     *
     * @param $class
     * @param $path
     * @param $namespace
     *
     * @return object
     * @throws
     */
    protected function resolveClass($class, $namespace)
    {
        $class = $namespace . str_replace('/', '\\', $class);
        if (!class_exists($class)) {
            return $this->exception($class . ' class is not found. Please, check file.');
        }
        return new $class();
    }

    /**
     * @param $function
     * @param $params
     *
     * @return mixed
     */
    protected function runMethodWithParams($function, $params)
    {
        $data = call_user_func_array($function, (!is_null($params) ? $params : []));
        return is_array($data) ? json_encode($data, JSON_UNESCAPED_UNICODE) : $data;
    }
}
