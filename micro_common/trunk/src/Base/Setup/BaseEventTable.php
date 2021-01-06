<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/1/24
 * Time: 14:15
 */

namespace Micro\Common\Base\Setup;


use Micro\Common\Contract\Middleware;
use Micro\Common\Base\Repository\BaseEventRepo;
use Micro\Common\Test\Events\DemoAfterEvent;
use Micro\Common\Test\Listeners\DemoListenerOne;
use Micro\Common\Test\Listeners\DemoListenerTwo;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BaseEventTable extends Middleware
{

    public $repo;

    public function __construct(BaseEventRepo $repo)
    {
        $this->repo = $repo;
    }
    
    public function handle($request, \Closure $next)
    {
        try{
            $this->createtable();
            $this->insertData();

            echo 'base event 初始化完毕'.PHP_EOL;
            return $next($request);
        }catch (\Exception $e){
            Err('base event 初始化错误');
        }
    }



    public function createtable()
    {
        Schema::dropIfExists('base_event');
        Schema::create('base_event', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->primary('id');
            $table->string('id',21)->comment('ID');
            $table->string('title',100);
            $table->string('event',100);
            $table->string('bean',255);
            $table->string('listener',255);
            $table->string('order',5)->comment('排序');
            $table->string('type',5)->comment('类型');
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
                'title'      =>'test1',
                'event'    =>'test.event',
                'bean'       =>DemoAfterEvent::class,
                'listener'  =>DemoListenerOne::class,
                'order' =>'10',
                'type' => '20',
                'status'     =>1,
                'create_time'=>date("Y-m-d H:i:s"),
                'update_time'=>date("Y-m-d H:i:s")
            ],
            [
                'id'         =>ID(),
                'title'      =>'test2',
                'event'    =>'test.event',
                'bean'       =>DemoAfterEvent::class,
                'listener'  =>DemoListenerTwo::class,
                'order' =>'20',
                'type' => '20',
                'status'     =>1,
                'create_time'=>date("Y-m-d H:i:s"),
                'update_time'=>date("Y-m-d H:i:s")
            ]
        ];
    }
}