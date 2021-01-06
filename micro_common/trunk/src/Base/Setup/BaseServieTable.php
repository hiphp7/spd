<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/1/9
 * Time: 15:51
 */

namespace Micro\Common\Base\Setup;


use Micro\Common\Contract\Middleware;
use Micro\Common\Base\Repository\BaseServiceRepo;
use Micro\Common\Project\Gre\Service\OneService;
use Micro\Common\Test\Service\TestService;
use Closure;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BaseServieTable extends Middleware
{

    public $repo;

    public function __construct(BaseServiceRepo $repo)
    {
        $this->repo = $repo;
    }


    public function handle($request, Closure $next)
    {

        try{
            $this->createtable();
            $this->insertData();
            echo 'base_service 初始化完毕'.PHP_EOL;
            return $next($request);
        }catch (\Exception $e){
            Err('base_service 初始化错误');
        }
    }


    public function createtable()
    {
        Schema::dropIfExists('base_service');
        Schema::create('base_service', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->primary('id');
            $table->string('id',21)->comment('ID');
            $table->string('title',100);
            $table->string('service',100);
            $table->string('bean',255);
            $table->smallInteger('status');
            $table->dateTime('create_time');
            $table->dateTime('update_time');
        });
    }


    public function insertData()
    {
        $data = $this->getData();
        $this->repo->insert($data);

    }

    public function getData()
    {
       return [
           [
               'id'         =>ID(),
               'title'      =>'test',
               'service'    =>'test.testservice',
               'bean'       =>TestService::class,
               'status'     =>1,
               'create_time'=>date("Y-m-d H:i:s"),
               'update_time'=>date("Y-m-d H:i:s")
           ],
           [
               'id'         =>ID(),
               'title'      =>'asdfasdone',
               'service'    =>'one',
               'bean'       =>OneService::class,
               'status'     =>1,
               'create_time'=>date("Y-m-d H:i:s"),
               'update_time'=>date("Y-m-d H:i:s")
           ]
       ];
    }

}