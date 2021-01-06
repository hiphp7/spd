<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/2
 * Time: 17:43
 */
namespace Micro\User;
use Micro\Common\Contract\ServiceProvider;

class UserProvider extends ServiceProvider
{
    public function register()
    {
        app()->router->group([
            'namespace' => 'Micro\User\Controller',
        ], function ($router) {
            require __DIR__ . '/router.php';
        });
    }

    public function boot()
    {
        $this->loadLocalConfig("user");

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