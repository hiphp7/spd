<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/21
 * Time: 16:52
 */

namespace App\Middleware;

/**
 * Class Parallel
 * @package App\Middleware
 * @desc 检测用户频繁操作 Redis
 */
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class Parallel
{
    public function handle(Request $request , \Closure $next)
    {
        $user_id = $request->user()->claims->getId();

        $key = $user_id.'_'.$request->path();

        if(Redis::setnx($key,$user_id)){
            $ret = $next($request);
            Redis::del($key);
            return $ret;
        }else{
            Err("操作频繁请稍后再试!");
        }

//
//        $value = Cache::get($key);
//
//        if($value){
//            Err("操作频繁请稍后再试!");
//        }else{
//            Cache::remember($key,1/6,function() use ($key){
//                return $key;
//            });
//            $rert= $next($request);
//            return $rert;
//        }

    }
}