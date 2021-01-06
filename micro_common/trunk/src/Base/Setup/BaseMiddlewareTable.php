<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/1/11
 * Time: 08:54
 */

namespace Micro\Common\Base\Setup;


use Micro\Common\Contract\Middleware;
use Micro\Common\Base\Repository\BaseMiddlewareRepo;
use Closure;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BaseMiddlewareTable extends Middleware
{

    public $repo;
    public function __construct(BaseMiddlewareRepo $repo)
    {
        $this->repo = $repo;
    }

    public function handle($request, Closure $next)
    {
        try{
            $this->createtable();
            $this->insertData();
            echo 'base_middleware 初始化完毕'.PHP_EOL;
            return $next($request);
        }catch (\Exception $e){
            Err('base_middleware 初始化错误');
        }
    }


    public function createtable()
    {
        Schema::dropIfExists('base_middleware');
        Schema::create('base_middleware', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->primary('id');
            $table->string('id',21)->comment('ID');
            $table->string('title',100);
            $table->string('middle',100);
            $table->string('bean',255);
            $table->smallInteger('status');
            $table->smallInteger('order');
            $table->dateTime('create_time');
            $table->dateTime('update_time');
        });
    }

}