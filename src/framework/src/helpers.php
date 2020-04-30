<?php
/**
 * APP 帮助函数库
 *
 * @author
 * @version     1.0 版本号
 */


/**
 * 获取配置
 *
 * @param string $name
 * @return array | string
 */
if (!function_exists('config')) {
    function config($name = '')
    {

        $files = [];
        $directory = APP_PATH . '/config';
        try {
            $dir = new DirectoryIterator($directory);
        } catch (Exception $e) {
            throw new Exception($directory . ' is not readable');
        }
        foreach ($dir as $file) {
            if ($file->isDot()) continue;
            $files[] = $file->getFileName();
        }
        $config = [];
        foreach ($files as $file) {
            if ($file != 'routes.php') {
                $basename = explode('.', $file)[0];
                $config[$basename] = include($directory . DIRECTORY_SEPARATOR . $file);
            }
        }
        return empty($name) ? $config : $config[$name];
    }
}

/**
 * 下载本地文件
 *
 * @return void
 */
if (!function_exists('download')) {
    function download($file, $filename = '')
    {
        $file = PUBLIC_PATH . $file;
        $fileType = substr($file, strripos($file, "."));
        $filename = $filename == '' ? basename($file) : $filename;
        $filename = $filename . $fileType;
        header("Content-Disposition:attachment;filename = " . $filename);
        header("Accept-ranges:bytes");
        header("Accept-length:" . filesize($file));
        readfile($file);
        exit();
    }
}


if (!function_exists('request'))
{
    function request($param)
    {
        if (in_array($_SERVER['REQUEST_METHOD'], ['PUT', 'DELETE']))
        {
            parse_str(file_get_contents("php://input"), $_REQUEST);
        }
        $param = $_REQUEST["$param"];
        $param = trim($param);
        $param = stripslashes($param);
        $param = htmlspecialchars($param);
        return trim($param);
    }
}

if (!function_exists('logWrite'))
{
    function logWrite($data, $type = 'app', $level = 'debug')
    {
        $path = STORAGE_PATH . '/logs' . date('/Y/m/');
        if (!is_dir($path)) mkdir($path,0755, true);
        $fileName = $path . "$type.log";
        if (is_array($data)) $data = json_encode($data, JSON_PRETTY_PRINT);
        $log = date('Y-m-d H:i:s') . ' ' . getIp() . ' ' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . PHP_EOL;
        $data = $log . "[ $level ]  " . $data . PHP_EOL;
        file_put_contents($fileName, $data, FILE_APPEND);
        return true;
    }
}

if (!function_exists('getIp'))
{
    function getIp(){
        $ip = '';
        if(getenv('HTTP_CLIENT_IP')&&strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')){
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif(getenv('HTTP_X_FORWARDED_FOR')&&strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown')){
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif(getenv('REMOTE_ADDR')&&strcasecmp(getenv('REMOTE_ADDR'),'unknown')){
            $ip = getenv('REMOTE_ADDR');
        } elseif(isset($_SERVER['REMOTE_ADDR'])&&$_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],'unknown')){
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}

if (!function_exists('formatDate'))
{
    function formatDate($time){
        $t = time() - $time;
        $f = [
            '31536000'=>'年',
            '2592000'=>'个月',
            '604800'=>'星期',
            '86400'=>'天',
            '3600'=>'小时',
            '60'=>'分钟',
            '1'=>'秒'
        ];
        foreach ($f as $k=>$v)    {
            if (0 != $c=floor($t / (int)$k)) {
                return $c.$v.'前';
            }
        }
    }
}


if (! function_exists('cloverEnv')) {
    /**
     * 获取环境变量
     *
     * @param  string  $varname
     * @param  mixed  $default
     * @return mixed
     */
    function cloverEnv($varname, $default = null)
    {
        $env = parse_ini_file(BASE_PATH . '/.env', true);
        foreach ($env as $key => $val) {
            $name = strtoupper($key);
            if (is_array($val)) {
                foreach ($val as $k => $v) {
                    $item = $name . '_' . strtoupper($k);
                    putenv("$item=$v");
                }
            } else {
                putenv("$name=$val");
            }
        }
        return getenv($varname) ? getenv($varname) : $default;
    }
}

/**
 * curl 请求
 *
 * @param string $url
 * @param array $data
 * @return mixed
 */
if(! function_exists('httpRequest')){
    //HTTP请求（支持HTTP/HTTPS，支持GET/POST）
    function httpRequest($url, $data = null)
    {
        if (function_exists('curl_init'))
        {
            throw new \Exception('CURL is not enabled');
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}

/**
 * http 跳转
 *
 */
if (!function_exists('redirect')) {
    /**
     * 获取\think\response\Redirect对象实例
     * @param mixed         $url 重定向地址 支持Url::build方法的地址
     * @param array|integer $params 额外参数
     * @param integer       $code 状态码
     * @return \think\response\Redirect
     */
    function redirect($url = [], $params = [], $code = 302)
    {
        if (is_integer($params)) {
            $code   = $params;
            $params = [];
        }
        header ("location: $url");
    }
}

/**
 * 成功返回方法
 *
 * @param array $data
 * @return string
 */
if (!function_exists('success')) {
    function success($data, $msg = '', $code = 0)
    {
        header('Access-Control-Allow-Origin:*'); // *代表允许任何网址请求
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
        header('Content-Type:application/json; charset=utf-8');
        return compact('code', 'msg', 'data');
    }
}
/**
 * 失败返回
 *
 * @param array $data
 * @return string
 */
if (!function_exists('error')) {
    function error($msg = '', $code = 100000, $data = [])
    {
        header('Access-Control-Allow-Origin:*'); // *代表允许任何网址请求
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
        header('Content-Type:application/json; charset=utf-8');
        return compact('code', 'msg', 'data');
    }
}

/**
 * 抓取远程图片
 *
 * @param string $url 远程图片路径
 * @param string $filename 本地存储文件名
 * @return string $filename
 */
if (!function_exists('downloadImg')) {
    function downloadImg($url, $filename = '')
    {
        if (!is_dir(dirname($filename))) {

            mkdir(dirname($filename), 0777, true);
        }
        if ($url == '') {
            return false; //如果 $url 为空则返回 false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $file = curl_exec($ch);
        curl_close($ch);
        $resource = fopen($filename, 'a');
        fwrite($resource, $file);
        fclose($resource);
        return $filename;
    }
}

/**
 * 压缩 HTML, 清除换行符,清除制表符,去掉注释标记
 *
 * @param $string
 * @return $string 压缩后的 HTML
 * */
if (!function_exists('downloadImg')) {
    function compressHtml($string)
    {
        $string = str_replace("\r\n", '', $string); //清除换行符
        $string = str_replace("\n", '', $string); //清除换行符
        $string = str_replace("\t", '', $string); //清除制表符
        $pattern = array(
            "/> *([^ ]*) *</", //去掉注释标记
            "/[\s]+/",
            "/<!--[^!]*-->/",
            "/\" /",
            "/ \"/",
            "'/\*[^*]*\*/'"
        );
        $replace = array(
            ">\\1<",
            " ",
            "",
            "\"",
            "\"",
            ""
        );
        return preg_replace($pattern, $replace, $string);
    }
}
