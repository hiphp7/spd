<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/20
 * Time: 14:46
 */

namespace App\Middleware;
use Closure;

class NoAction
{
    public function handle($request, Closure $next)
    {
        //开启后 屏蔽操作
//        Err("服务器加固中，马上回来!");

        return $next($request);
    }
}