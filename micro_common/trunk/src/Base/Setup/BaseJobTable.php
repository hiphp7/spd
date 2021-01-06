<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/4/11
 * Time: 17:49
 */

namespace Micro\Common\Base\Setup;


use Micro\Common\Contract\Middleware;
use Closure;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BaseJobTable extends Middleware
{
    public function handle($request, Closure $next)
    {
        try{
            $this->createtable();
            echo 'base_job 初始化完毕'.PHP_EOL;
            return $next($request);
        }catch (\Exception $e){
            Err('base_job 初始化错误');
        }
    }

    public function createtable()
    {
        Schema::dropIfExists('base_job');
        Schema::create('base_job', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->primary('id');
            $table->string('id',21)->comment('ID');
            $table->string('title',100);
            $table->string('job',100);
            $table->string('bean',255);
            $table->smallInteger('status');
            $table->smallInteger('order');
            $table->dateTime('create_time');
            $table->dateTime('update_time');
        });
    }

}