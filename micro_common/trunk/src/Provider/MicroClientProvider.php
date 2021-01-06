<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/8/2
 * Time: 10:56
 */

namespace Micro\Common\Provider;


use Micro\Common\Contract\ServiceProvider;

class MicroClientProvider extends ServiceProvider
{

    public function register()
    {
        app()->singleton('micro-client', function () {
            $driver = config('base.service_driver');
            return app()->make(config('base.protocol.'.$driver));
        });
    }

    public function boot()
    {
        //合并config//从当前config

        $this->loadLocalConfig();

    }

    public function loadLocalConfig($name = null)
    {
        if($name){
            $path = __DIR__.'/../config/'.$name.'.php';
            if(file_exists($path)){
                $this->mergeConfigFrom($path,$name);
            }
        }
    }
}