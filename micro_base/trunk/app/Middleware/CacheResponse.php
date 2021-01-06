<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/7/4
 * Time: 13:55
 */

namespace App\Middleware;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CacheResponse
{
    protected $minutes = 10;

    public function handle(Request $request , \Closure $next,$minutes = null)
    {
//        if(config('app.env') == 'development'){
//            return $next($request);
//        }
        $user_id = $request->user()->claims->getId();
        $path =md5($request->path());
        $input = md5(json_encode($request->all()));
        $key = $user_id.'_'.$path.'_'.$input;
        $period = $this->resolveMinutes($minutes);
        return Cache::remember($key,$period,function()use ($next,$request){
            return $next($request);
        });
    }

    protected function resolveMinutes($minutes)
    {
        return is_null($minutes)?$this->minutes:max($this->minutes,intval($minutes));
    }
}