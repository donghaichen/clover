<?php
/**
 * Created by PhpStorm.
 * User: donghai
 * Date: 16/2/3
 * Time: 15:30
 */

namespace Illuminate\Contracts\Database;


class HomeController extends BaseController
{

    public function home()
    {
        return array(
            'title'     => 'Hello Clover!',
            'content'   => '愿您的 API 像四叶草一样,集万千美好于一身!',
            'version'   => '1.0.0 beta',
            'time'      => time(),
        );
    }
}