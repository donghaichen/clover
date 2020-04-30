<?php

namespace App\Http\Middlewares;

use Closure;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param
     * @return void
     */
    public function __construct($auth = [])
    {
        $this->auth = $auth;
    }



    //        if (!$this->auth) {
//            return response('Unauthorized.', 401);
//        }
//
//        return $next($request);
    /**
     * Handle an incoming request.
     *
     * @param
     * @param  \Closure  $next
     * @param
     * @return mixed
     */
//    public function handle($request, Closure $next, $guard = null)
    public function handle()
    {
        return true;
    }
}
