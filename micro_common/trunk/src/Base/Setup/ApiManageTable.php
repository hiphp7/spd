<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/4/16
 * Time: 13:33
 */

namespace Micro\Common\Base\Setup;


use Micro\Common\Contract\Middleware;
use Closure;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApiManageTable extends Middleware
{

    public function handle($request, Closure $next)
    {

        try{
            $this->createtable();
            echo 'base_api_manage 初始化完毕'.PHP_EOL;
            return $next($request);
        }catch (\Exception $e){
            Err('base_api_manage 初始化错误');
        }
    }


    public function createtable()
    {
        Schema::dropIfExists('base_api_manage');
        Schema::create('base_api_manage', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->primary('id');
            $table->string('id',21)->comment('ID');
            $table->string('uri',255);
            $table->string('method',100);
            $table->string('controller',255);
            $table->string('action',255);
            $table->string('as',255);
            $table->string('desc',255);
            $table->text('rules');
            $table->text('document');


        });
    }


}