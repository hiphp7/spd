<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/22
 * Time: 15:07
 */

namespace App\Middleware;
use Closure;
use Illuminate\Http\Request;

/**
 * @desc PMS接口IP验证
 */
class PmsApiVerify
{
    public function handle(Request $request , Closure $next)
    {

        $request->setTrustedProxies(array('172.26.141.232'));
        $ip = $request->getClientIp();
        if($ip != '172.26.141.232') Err('系统错误');

        return $next($request);
    }
}