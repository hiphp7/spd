<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/1/9
 * Time: 13:43
 */

namespace Micro\Common\Base\Setup;


use Micro\Common\Contract\Middleware;
use Micro\Common\Base\Repository\UserCodeMasterRepo;
use Closure;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class UserCodeMasterTable extends Middleware
{

    public $repo;
    public function __construct(UserCodeMasterRepo $repo)
    {
        $this->repo = $repo;
    }

    public function handle($request, Closure $next)
    {

        try{
            $this->createtable();
            $this->insertData();
            echo 'user_code_master 初始化完毕'.PHP_EOL;
            return $next($request);
        }catch (\Exception $e){
            Log::info('错误信息----- | '.json_encode($e));
            Err('user_code_master 初始化错误');
        }
    }




    public function createtable(){
        Schema::dropIfExists('user_code_master');
        Schema::create('user_code_master', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->primary('id');
            $table->string('id',21)->comment('ID');
            $table->string('user_id',21);
            $table->string('code',30);
            $table->string('code_key',30);
            $table->string('property1',100)
                ->nullable()->comment('标题-属性1');
            $table->string('property2',100)
                ->nullable()->comment('属性2');
            $table->string('property3',100)
                ->nullable()->comment('属性3');
            $table->string('property4',100)
                ->nullable()->comment('属性4');
            $table->string('property5',100)
                ->nullable()->comment('属性5');
            $table->string('property6',100)
                ->nullable()->comment('属性6');
            $table->string('property7',100)
                ->nullable()->comment('属性7');
            $table->string('property8',100)
                ->nullable()->comment('属性8');
            $table->string('property9',100)
                ->nullable()->comment('属性9');
            $table->string('property10',100)
                ->nullable()->comment('属性10');
            $table->dateTime('create_time');
            $table->dateTime('update_time');

        });
    }

    public function insertData(){
        $data = $this->getData();
        $this->repo->insert($data);

    }


    public function getData()
    {
        return [
            [
                'id'=>ID(),
                'user_id' =>'1192472444930161152',
                'code' =>'profitK0650',
                'code_key' =>'DirectProfitAmount',
                'property1' =>'直推分润比例',
                'property2' =>'0.04',
                'property3' =>'0.01',
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s"),
            ]
        ];
    }

}