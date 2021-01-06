<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/1/17
 * Time: 11:58
 */

namespace Micro\Common\Base\Setup;


use Micro\Common\Contract\Middleware;
use Closure;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class BaseActivityTable extends  Middleware
{

    public function handle($request, Closure $next)
    {
        try{
            $this->createtable();
            echo 'base activity 初始化完毕'.PHP_EOL;
            return $next($request);
        }catch (\Exception $e){
            Log::info('错误信息----- | '.json_encode($e));
            Err('base_activity 初始化错误');
        }
    }

    public function createtable()
    {
        $this->activity();
        $this->activityOrder();
    }

    public function activity()
    {

        Schema::dropIfExists('base_activity');
        Schema::create('base_activity', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->primary('id');
            $table->string('id',21)->comment('ID');
            $table->string('title',100)->comment('标题');
            $table->string('code',5)->comment('业务码');
            $table->string('middle',100)->comment('中间件');

            $table->string('introduce',255)->comment('描述');
            $table->text('detail')->comment('详情');
//
            $table->decimal('price',8,2)->comment('活动价格');
            $table->decimal('point_price',8,2)->comment('活动积分');
            $table->decimal('coin_price',8,2)->comment('活动金币');
//
            $table->decimal('reward_asset',8,2)->comment('返点金额');
            $table->decimal('reward_point',8,2)->comment('返点积分');
            $table->decimal('reward_coin',8,2)->comment('返点金币');

            $table->integer('inventory')->comment('供应量');
//            $table->dateTime('start_time')->comment('开始时间');
//            $table->dateTime('end_time')->comment('结束时间');
//
//            // P1101,P1201,P1301.....
//            $table->string('level',100)->comment('限定等级');
//            $table->dateTime('create_time');
//            $table->dateTime('update_time');


        });

    }

    public function activityOrder()
    {
        Schema::dropIfExists('base_activity_order');
        Schema::create('base_activity_order', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->primary('id');
            $table->string('id', 21)->comment('ID');
            $table->string('activity_id', 21)->comment('活动ID');
            $table->string('title',100)->comment('活动标题');

            $table->string('user_id', 21)->comment('用户ID');
            $table->string('user_name', 10)->comment('用户名');
            $table->string('user_mobile', 11)->comment('用户手机');
            $table->string('user_level', 5)->comment('用户等级');

            $table->string('code',5)->comment('业务码');
            $table->string('middle',100)->comment('中间件');

            $table->decimal('price',8,2)->comment('活动价格');
            $table->decimal('point_price',8,2)->comment('活动积分');
            $table->decimal('coin_price',8,2)->comment('活动金币');

            $table->decimal('reward_asset',8,2)->comment('返点金额');
            $table->decimal('reward_point',8,2)->comment('返点积分');
            $table->decimal('reward_coin',8,2)->comment('返点金币');
            $table->dateTime('create_time');
            $table->dateTime('update_time');

            //支付成功流水,只有成功时更新,方便可查
            $table->string('pay_order_id',21)->comment('支付流水');

        });
    }



}