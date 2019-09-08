<?php
declare (strict_types = 1);

namespace App;

use Psr\Log\LoggerInterface;


/**
 * 日志类
 *
 * 日志类应该严格按照 PSR3 日志规范开发。 More see https://www.php-fig.org/psr/psr-3/
 * @author      Donghai Chen
 * @version     1.0
 */
class Log implements LoggerInterface
{
    const EMERGENCY = 'emergency';
    const ALERT     = 'alert';
    const CRITICAL  = 'critical';
    const ERROR     = 'error';
    const WARNING   = 'warning';
    const NOTICE    = 'notice';
    const INFO      = 'info';
    const DEBUG     = 'debug';
    const SQL       = 'sql';

    public function __call($method, $parameters)
    {
        $this->log($method, ...$parameters);
    }

    /**
     * 记录日志信息
     * @access public
     * @param mixed  $msg     日志信息
     * @param string $type    日志级别
     * @param array  $context 替换内容
     * @param bool   $lazy
     * @return $this
     */
    public function record($msg, string $type = 'info', array $context = [], bool $lazy = true)
    {
        global $config;
        $config = $config['log'];
        $dir = $config['path'];
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, true);
        }
        $file = $dir . DIRECTORY_SEPARATOR . $type . '.log';
        $content = $msg . json_encode($context);
        $date = date($config['date_format']);
        $content = "[$date] [$type] $content" . PHP_EOL;
        file_put_contents($file, $content, FILE_APPEND);
        return $this;
    }


    /**
     * 记录emergency信息
     * @access public
     * @param mixed $message 日志信息
     * @param array $context 替换内容
     * @return void
     */
    public function emergency($message, array $context = []): void
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * 记录警报信息
     * @access public
     * @param mixed $message 日志信息
     * @param array $context 替换内容
     * @return void
     */
    public function alert($message, array $context = []): void
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * 记录紧急情况
     * @access public
     * @param mixed $message 日志信息
     * @param array $context 替换内容
     * @return void
     */
    public function critical($message, array $context = []): void
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * 记录错误信息
     * @access public
     * @param mixed $message 日志信息
     * @param array $context 替换内容
     * @return void
     */
    public function error($message, array $context = []): void
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * 记录warning信息
     * @access public
     * @param mixed $message 日志信息
     * @param array $context 替换内容
     * @return void
     */
    public function warning($message, array $context = []): void
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * 记录notice信息
     * @access public
     * @param mixed $message 日志信息
     * @param array $context 替换内容
     * @return void
     */
    public function notice($message, array $context = []): void
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * 记录一般信息
     * @access public
     * @param mixed $message 日志信息
     * @param array $context 替换内容
     * @return void
     */
    public function info($message, array $context = []): void
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * 记录调试信息
     * @access public
     * @param mixed $message 日志信息
     * @param array $context 替换内容
     * @return void
     */
    public function debug($message, array $context = []): void
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * 记录sql信息
     * @access public
     * @param mixed $message 日志信息
     * @param array $context 替换内容
     * @return void
     */
    public function sql($message, array $context = []): void
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * 记录日志信息
     * @access public
     * @param string $level   日志级别
     * @param mixed  $message 日志信息
     * @param array  $context 替换内容
     * @return void
     */
    public function log($level, $message, array $context = []): void
    {
        $this->record($message, $level, $context);
    }
}
