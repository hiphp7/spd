<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/25
 * Time: 08:45
 */

namespace Micro\Common\Contract;


use Closure;

abstract class Middleware {

    public function dispatch($job)
    {
        return app('Illuminate\Contracts\Bus\Dispatcher')->dispatch($job);
    }

    abstract public function handle($request , Closure $next);

}