<?php
namespace App\Models;

class Bing extends Model
{

    protected $table = 'bing';
    public $timestamps = false;

    public static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
    }

}