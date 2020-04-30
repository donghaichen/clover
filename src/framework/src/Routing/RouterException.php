<?php
/**
 * @Package: Clover RouterException
 * @Class  : RouterException
 * @Author : @donghaichen <chendongahai888@gmai.com>
 * @URL    : https://github.com/donghaichen
 * @URL    : https://github.com/izniburak/php-router
 */

namespace Clover\Routing;

use Exception;

class RouterException
{
    /**
     * @var bool $debug Debug mode
     */
    public static $debug = false;

    /**
     * Create Exception Class.
     *
     * @param $message
     *
     * @return string
     * @throws Exception
     */
    public function __construct($message)
    {
        if (self::$debug) {
            throw new Exception($message, 1);
        } else {
            die('<h2>Opps! An error occurred.</h2> ' . $message);
        }
    }
}
