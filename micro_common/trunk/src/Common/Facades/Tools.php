<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/2/21
 * Time: 09:17
 */

namespace Micro\Common\Common\Facades;


use Micro\Common\Common\Util\Tool;
use Illuminate\Support\Facades\Facade;

class Tools extends Facade
{
    public static function getFacadeAccessor(){
        return app(Tool::class);
    }
}