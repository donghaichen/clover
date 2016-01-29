<?php
/*
|--------------------------------------------------------------------------
| MODE（用户模型）文件
|--------------------------------------------------------------------------
|
| （CONTROLLER)控制器不可直接与数据库打交道，必须要经过MODEL(模型）
|
*/

use  Illuminate\Database\Eloquent\Model  as Eloquent;

class User extends  Eloquent
{
    protected $table = 'users';
}
//// 查询id为2的
//$users = User::find(2);
//
//// 查询全部
//$users = User::all();
//
//// 创建数据
//$user = new User;
//$user->username = 'someone';
//$user->email = 'some@overtrue.me';
//$user->save();