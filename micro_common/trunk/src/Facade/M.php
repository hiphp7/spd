<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/8/7
 * Time: 15:53
 */
namespace Micro\Common\Facade;

use Illuminate\Support\Facades\Facade;
use Micro\Common\Tool\Mock;

class M extends Facade
{
    public static function getFacadeAccessor(){
        return app(Mock::class);
    }
}