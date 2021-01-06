<?php
namespace  Micro\Common\Provider;

use Micro\Common\Console\SwooleThriftServer;
use Micro\Common\Contract\ServiceProvider;
use Micro\Common\Thrift\Client\ThriftClient;

/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/7/17
 * Time: 09:46
 */

class SwooleThriftServiceProvider extends ServiceProvider
{
    protected $commands =[
        SwooleThriftServer::class,
    ];

    public function register(){

        app()->singleton('micro-thrift', function () {
            return app()->make(ThriftClient::class);
        });
        $this->commands($this->commands);

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