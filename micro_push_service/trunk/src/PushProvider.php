<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/8
 * Time: 11:33
 */
namespace Micro\Push;
use Micro\Common\Contract\ServiceProvider;

class PushProvider extends ServiceProvider
{
    public function register()
    {
        app()->router->group([
            'namespace' => 'Micro\Push\Controller',
        ], function ($router) {
            require __DIR__ . '/router.php';
        });
    }

    public function boot()
    {
        $this->loadLocalConfig("jpush");

    }

    public function loadLocalConfig($name = null)
    {
        if($name){
            $path = __DIR__ . '/../config/' .$name.'.php';
            if(file_exists($path)){
                $this->mergeConfigFrom($path,$name);
            }
        }
    }
}