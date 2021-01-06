<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/8/5
 * Time: 13:00
 */

namespace Micro\Common\Base\Setup;


use Closure;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Micro\Common\Contract\Middleware;

class BaseBroadcastTable extends Middleware
{
    public function handle($request, Closure $next)
    {
        try{
            $this->createtable();
            echo 'base_broadcast 初始化完毕'.PHP_EOL;
            return $next($request);
        }catch (\Exception $e){
            Err('base_broadcast 初始化错误');
        }
    }
    public function createtable()
    {
        Schema::dropIfExists('base_broadcast');
        Schema::create('base_broadcast', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->primary('id');
            $table->string('id',21)->comment('ID');
            $table->string('title',100);
            $table->string('broadcast',100);
            $table->string('bean',255);
            $table->smallInteger('status');
            $table->smallInteger('order');
            $table->dateTime('create_time');
            $table->dateTime('update_time');
        });
    }

}