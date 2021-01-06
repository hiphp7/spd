<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/28
 * Time: 17:22
 */
namespace Micro\Sms;
use Micro\Common\Contract\ServiceProvider;

class SmsProvider extends ServiceProvider
{

    public function register()
    {
        app()->router->group([
            'namespace' => 'Micro\Sms\Controller',
        ], function ($router) {
            require __DIR__ . '/router.php';
        });
    }

    public function boot()
    {
        $this->loadLocalConfig("sms");

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