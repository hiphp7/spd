<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/2
 * Time: 10:03
 */
namespace Micro\Statistics\Controller;

use Illuminate\Http\Request;
use Micro\Common\Base\Base;
use Micro\Common\Contract\Controller;
use Micro\Common\Facade\M;

class StatisticsController extends Controller
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    /**
     * @desc 今日拒单数量 通过Lua脚本Redis中获取
     * @param Request $request
     */
    public function refusalOrder(Request $request){

    }

    public function refusalOrderMockReturn(){
        return M::number(1);
    }

    /**
     * @desc 获取车主当日数据
     * @param Request $request
     * @return array
     * @return string today_income 当日的收入金额
     * @return string today_order_num 当日的订单数量
     * @return string today_duration 在线的时长
     * @return string today_deal_rate 当日订单的成交率
     */
    public function getDriverStatisticsDate(Request $request){
        $driver_id = $request->user()->claims->getId();

        $code = Base::code('redis_db.data_statistics');
        $redis = getConnections([
            'name' => 'default_'.$code['property2'],
            'database' => $code['property2']
        ]);
        $key = env('QUEUE_NAME').'_driver_daile_statistics_data:'.$driver_id;

        $data = $redis->hgetall($key);
        if(!$data){
            $ret = [
                'today_income' => 0,
                'today_order_num' => 0,
                'today_duration' => 0,
                'today_deal_rate' => 100,
            ];
        }else{
            $hour = floor((time() - strtotime($data['online_time']))/86400/3600);
            $hour += $data['today_online_duration'];
            $ret = [
                'today_income' => $data['today_income'],
                'today_order_num' => $data['today_order_num'],
                'today_duration' => $hour,
                'today_deal_rate' => $data['today_deal_rate'],
            ];
        }

        return $ret;
    }

    public function getDriverStatisticsDateMockReturn(){
        $ret = [
            'today_income' => M::number(2),
            'today_order_num' => M::price(2),
            'today_duration' => M::number(1),
            'today_deal_rate' => M::number(2).'%',
        ];
        return $ret;
    }
}