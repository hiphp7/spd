<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/7/30
 * Time: 15:02
 */

namespace App\Middleware;

class RequestId
{
    public function handle($request, \Closure $next)
    {
        config(['REQUEST_ID'=>rand(100000000,999999999)]);
        return $next($request);
    }
}