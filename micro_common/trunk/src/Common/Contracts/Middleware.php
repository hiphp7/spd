<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/25
 * Time: 08:45
 */

namespace Micro\Common\Common\Contracts;


use Closure;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

abstract class Middleware {
    use ProvidesConvenienceMethods;
    abstract public function handle($request , Closure $next);

}