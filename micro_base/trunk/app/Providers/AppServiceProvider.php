<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        app()->singleton('filesystem', function ($app) {
            return app()->loadComponent('filesystems', 'Illuminate\Filesystem\FilesystemServiceProvider', 'filesystem');
        });
    }

    public function boot()
    {
        app()->configure('app');
        //debug模式开启sql跟踪
        if (Config::get('app.debug')) {
            app('db')->enableQueryLog();
        }
    }
}
