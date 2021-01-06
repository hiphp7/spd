<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/2/22
 * Time: 10:02
 */

namespace Micro\Common\Common\Facades;


use Micro\Common\Common\Service\TaskService;
use Illuminate\Support\Facades\Facade;

class Task extends Facade
{
    public static function getFacadeAccessor(){
        return app(TaskService::class);
    }
}