<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/7/12
 * Time: 13:02
 */

namespace App\Providers;

use App\Handlers\ConsoleJsonLoggerHandler;
use App\Handlers\JsonLoggerHandler;
use Micro\Common\Contract\ServiceProvider;
use Monolog\Handler\BufferHandler;

class JsonLogServiceProvider extends ServiceProvider
{
    public function register()
    {
        //自定义日志配置

        if(app()->runningInConsole()){
            app()->configureMonologUsing(function ($monolog) {
                $handler = new ConsoleJsonLoggerHandler();
                return $monolog->pushHandler($handler);
            });

        }else{
            app()->configureMonologUsing(function ($monolog) {
                $handler = new JsonLoggerHandler();
                return $monolog->pushHandler(new BufferHandler($handler));
            });
        }
    }
}