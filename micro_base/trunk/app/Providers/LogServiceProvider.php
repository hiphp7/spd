<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/7/10
 * Time: 12:56
 */

namespace App\Providers;

use Micro\Common\Contract\ServiceProvider;

class LogServiceProvider extends ServiceProvider
{

    public function register()
    {
        //自定义日志配置

        if(app()->runningInConsole()){

            app()->configureMonologUsing(function ($monolog) {
                $handler = new \App\Handlers\ConsoleLoggerHandler();
                return $monolog->pushHandler($handler);
            });

        }else{
            app()->configureMonologUsing(function ($monolog) {
                $handler = new \App\Handlers\LoggerHandler();
                return $monolog->pushHandler(new \Monolog\Handler\BufferHandler($handler));
            });
        }
    }

}