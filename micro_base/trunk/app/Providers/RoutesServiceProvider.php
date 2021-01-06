<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/23
 * Time: 09:00
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RoutesServiceProvider extends ServiceProvider
{

    public function register()
    {
        app()->router->group([
            'namespace' => 'App\Controller',
        ], function ($router) {
            require __DIR__ . '/../router.php';
        });
    }

    public function boot()
    {

    }

}