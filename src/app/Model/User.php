<?php

/**

 * User Model

 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model

{

//    public $table = 'users';//这样寻找的就是没s的表
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
//        'name', 'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

}