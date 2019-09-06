<?php

// 加载配置
use App\Mysql;

$config = config();

// Eloquent ORM
$database = $config['database'];
function DB($table = '')
{
    global $database;
    if (empty($table))
    {
        $db = new Mysql($database);
    }else{
        $db = new Mysql($database, $table);
    }
    return $db;
}

// whoops 错误提示
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

class app {
    /**
     * Run application
     *
     * This method traverses the application middleware stack and then sends the
     * resultant Response object to the HTTP client.
     *
     * @param ServerRequestInterface|null $request
     * @return void
     */
    public function run($request = null)
    {
        // 加载路由
        require APP_PATH . '/config/routes.php';
    }

}
