<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/8/5
 * Time: 13:53
 */

namespace Micro\Common\Base\Setup;


use Closure;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Micro\Common\Contract\Middleware;

class BaseBroadcastChannel extends Middleware
{
    public function handle($request, Closure $next)
    {
        try{
            $this->createtable();
            echo 'base_broadcast_channel 初始化完毕'.PHP_EOL;
            return $next($request);
        }catch (\Exception $e){
            Err('base_broadcast_channel 初始化错误');
        }
    }
    public function createtable()
    {
        Schema::dropIfExists('base_broadcast_channel');
        Schema::create('base_broadcast_channel', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->primary('id');
            $table->string('id',21)->comment('ID');
            $table->string('title',100);
            $table->string('broadcast_service',255);
            $table->string('channel_micro',255);
            $table->smallInteger('status');
            $table->smallInteger('order');
            $table->dateTime('create_time');
            $table->dateTime('update_time');
        });
    }

}