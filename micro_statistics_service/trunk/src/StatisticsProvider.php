<?php
namespace Micro\Statistics;
use Micro\Common\Contract\ServiceProvider;

/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/8/28
 * Time: 15:23
 */

class StatisticsProvider extends ServiceProvider
{

    public function register()
    {
        app()->router->group([
            'namespace' => 'Micro\Statistics\Controller',
        ], function ($router) {
            require __DIR__ . '/router.php';
        });
    }

    public function boot()
    {
        $this->loadLocalConfig("Statistics");

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