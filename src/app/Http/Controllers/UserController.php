<?php


namespace App\Http\Controllers;



use App\Model\User;

class UserController
{
    public function list()
    {
        return User::get()->toArray();
    }
}
