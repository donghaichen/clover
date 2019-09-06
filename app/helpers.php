<?php

function config($name = '')
{
    //配置环境变量
    env();

    //配置开始
    $files = [];
    $directory = APP_PATH . '/config';
    try {
        $dir = new DirectoryIterator($directory);
    } catch (Exception $e) {
        throw new Exception($directory . ' is not readable');
    }
    foreach($dir as $file) {
        if($file->isDot()) continue;
        $files[] = $file->getFileName();
    }
    $config = [];
    foreach ($files as $file)
    {
        if ($file != 'routes.php')
        {
            $basename = explode('.', $file)[0];
            $config[$basename] = include($directory . DIRECTORY_SEPARATOR . $file);
        }
    }
    return empty($name) ? $config : $config[$name];
}

/*
 * 下载本地文件
 */
function download($file, $filename = '')
{
    $file = PUBLIC_PATH . $file;
    $fileType = substr($file,strripos($file,"."));
    $filename = $filename == '' ? basename($file) : $filename;
    $filename = $filename . $fileType;
    header("Content-Disposition:attachment;filename = " . $filename);
    header("Accept-ranges:bytes");
    header("Accept-length:" . filesize($file));
    readfile($file);
    exit();
}

if(!function_exists('env'))
{
    function env()
    {
        $env_file = APP_PATH . '/.env';
        $env = parse_ini_file($env_file, true);
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
    }
}

if(! function_exists('httpRequest')){
    //HTTP请求（支持HTTP/HTTPS，支持GET/POST）
    function httpRequest($url, $data = null)
    {
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

function success($data,$msg = '',  $code = 0)
{
    header('Access-Control-Allow-Origin:*'); // *代表允许任何网址请求
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
    header('Content-Type:application/json; charset=utf-8');
    $data = compact('code', 'msg', 'data');
    echo json_encode($data);
    exit();
}

function error($msg = '', $code = 100000, $data = [])
{
    header('Access-Control-Allow-Origin:*'); // *代表允许任何网址请求
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
    header('Content-Type:application/json; charset=utf-8');
    $data = compact('code', 'msg', 'data');
    echo json_encode($data);
    exit();
}

//
/**
 * 抓取远程图片
 *
 * @param string $url 远程图片路径
 * @param string $filename 本地存储文件名
 */
function downloadImg($url, $filename = '')
{
    if (!is_dir(dirname($filename)))
    {

        mkdir(dirname($filename), 0777, true);
    }
    if($url == '') {
        return false; //如果 $url 为空则返回 false;
    }
//    $ext_name = strrchr($url, '.'); //获取图片的扩展名
//    $ext_name = str_replace('&pid=hp', '', $ext_name); //获取图片的扩展名
//    if($ext_name != '.gif' && $ext_name != '.jpg' && $ext_name != '.bmp' && $ext_name != '.png') {
//        return false; //格式不在允许的范围
//    }
//    $filename = $filename . $ext_name; //以时间戳另起名
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
/**
 * 压缩html : 清除换行符,清除制表符,去掉注释标记
 * @param $string
 * @return 压缩后的$string
 * */
function compressHtml($string) {
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
