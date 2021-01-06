<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/8/2
 * Time: 16:16
 */

namespace Micro\Common\Provider;

use Illuminate\Support\Facades\Broadcast;
use Micro\Common\Broadcast\RedisQueueBroadcast;
use Micro\Common\Contract\ServiceProvider;

class ExtendBroadcastProvider extends ServiceProvider
{

    public function register(){

        Broadcast::extend('redis',function ($config){
            return new RedisQueueBroadcast(
                $this->app->make('redis'), $config['connection'] ?? null
            );
        });
    }
}