<?php

//包含Eloquent的初始化文件
include '../Start.php';

use Illuminate\Database\Capsule\Manager as DB;

DB::schema()->create('users', function($table)
{
    $table->increments('id');
    $table->string('username', 40);
    $table->string('email')->unique();
    $table->string('password');
    $table->timestamps();
});

