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
        return $this->json(['app' => 'Rester', 'message' => 'Hello world!']);
    }
}