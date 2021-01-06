<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/1/7
 * Time: 13:25
 */

namespace Micro\Common\Base;


use Illuminate\Support\Facades\Facade;

class Base extends Facade
{
    public static function getFacadeAccessor(){
        return 'micro-base';
    }
}