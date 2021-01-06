<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/21
 * Time: 10:49
 */

namespace Micro\User\Controller;


use Micro\Common\Base\Base;
use Micro\Common\Contract\Controller;
use Illuminate\Http\Request;
use Micro\User\Service\TripOrderService;

class PassengerTripOrderController extends Controller
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    /**
     * @desc 乘客获取行程中订单
     * @param Request $request
     * @return array
     * @return string type 0010:未下单(没有订单)  0020:订单调度中  0030:行程中
     * @return string status 订单状态 0000 :订单完成,已支付 0010 :司机接单 0020 :接到乘客,行程中 0030:行程结束,报价成功,等待支付  0040:报价失败,不可支付
     * @return string id 订单ID
     * @return string passenger_id 乘客ID
     * @return string driver_id 司机ID
     * @return string driver_name 司机名称
     * @return string dep_longitude 出发地点经度
     * @return string dep_latitude 出发地点纬度
     * @return string dep_are 上车地点
     * @return string dep_time 上车时间
     * @return string dest_longitude 目的地地点经度
     * @return string dep_latitude 目的地地点纬度
     * @return string dest_area 下车地点
     * @return string dest_time 下车时间
     * @return string drive_mile 载客里程
     * @return string drive_time 载客时间
     * @return string fact_price 实收金额
     * @return string benfit_price 优惠金额
     * @return string pay_state 结算状态 0000已结算 0010：未结算 0020：未知
     * @return string pay_time 结算状态 乘客结算时间
     * @return string trid 高德轨迹ID
     * @return string receive_time 司机接单时间
     * @return string sum_cost 订单总价
     * @return string start_cost 起步价
     * @return string time_cost 时间费用
     * @return string distance_cost 里程费用
     * @return string status 订单状态 0000 :订单完成,已支付 0010 :司机接单 0015:到达乘客上车点 0020 :接到乘客,行程中 0030:行程结束,报价成功,等待支付  0040:报价失败,不可支付 0050:发起收款 0060:订单取消
     * @return string order_type  '0010 : 实时订单  0020:预约订单 0030:顺风车单  0040:城际订单'
     * @return string update_time  更新时间
     * @return string passenger_name  乘客名称
     * @return string passenger_headimgurl  乘客头像
     * @return string driver_headimgurl  司机头像
     */
    public function getOnTripOrderByPassengerId(Request $request){
        //1.判断有没有派送中订单
        //2.判断乘客有没有行程中订单
        //3.获取行程中订单

        $passenger_id = $request->user()->claims->getId();

        $code = Base::code('redis_db.passenger');

        $redis = getConnections([
            'name' => 'default_'.$code['property2'],
            'database' => $code['property2']
        ]);

        $passengerData = $redis->hgetall(env('QUEUE_NAME')."_passenger_online:".$passenger_id);
        if(!$passengerData) return [];
        $data = [];

        //0010:未下单  0020:订单调度中  0030:行程中
        switch ($passengerData['current_state']){
            case '0010' :
                $data['type'] = '0010';
                $data['data'] = [];
                break;
            case '0020':
                $data['type'] = '0020';
                $orderData = $this->getOrderWaitDispatch($passenger_id);
                $data['data'] = $orderData;
                $data['order_type'] = $orderData['order_type'];
                break;
            case '0030':
                $data['type'] = '0030';
                $orderData = $this->getInTripOrder($passenger_id);
                $data['data'] = $orderData;
                $data['order_type'] = $orderData['orderData']['order_type'];
                break;
        }

        return $data;
    }

    /**
     * @desc 获取正在派送订单信息
     * @param $passenger_id
     * @return array
     */
    public function getOrderWaitDispatch($passenger_id){
        $code = Base::code('redis_db.order_realtime');
        $redis = getConnections([
            'name' => 'default_'.$code['property2'],
            'database' => $code['property2']
        ]);

        $orderData = $redis->hgetall(env('QUEUE_NAME').'_trip_order:'.$passenger_id);

        $origins = $orderData['dep_longitude'] .','.$orderData['dep_latitude'];
        $destination = $orderData['dest_longitude'] .','.$orderData['dest_latitude'];
        $distance = $this->getGouldDistance($origins,$destination);

        $start_time = date('Y-m-d H:i:s');
        $end_time = date('Y-m-d H:i:s',strtotime($start_time) + $distance['duration']);

        $priceDetails = app('micro-client')
            ->micro('CostCalculationService')
            ->service('Micro\OrderCash\Service\CostCalculationService')
            ->with('start_time',$start_time)   //乘客上车时间
            ->with('end_time',$end_time)//到达目的地,行程结束时间
            ->with('distance',$distance['distance'] / 1000)
            ->run('testBilling');

        $orderData['cost'] = $priceDetails['data']['cost'];
        return $orderData;
    }

    /**
     * @desc 获取行程中订单信息
     * @param $passenger_id
     * @return array
     */
    public function getInTripOrder($passenger_id){
        $ret = app('micro-client')
            ->micro('GetTripOrderListService')
            ->service('Micro\\OrderDispatch\\Service\\GetTripOrderListService')
            ->with('passenger_id',$passenger_id)
            ->run('getOnTripOrderByPassengerId');

        return Base::service(TripOrderService::class)
            ->with('user_id',$passenger_id)
            ->with('order_id',$ret['data']['id'])
            ->run('getTripOrderInfo');
    }

    /**
     * @desc 根据起点终点查询距离
     * @param $origins
     * @param $destination
     * @return mixed
     */
    public function getGouldDistance($origins,$destination){
        $ret = app('micro-client')
            ->micro('RestApiService')
            ->service('Micro\\OrderDispatch\\Service\\RestApiService')
            ->with('origins',$origins)
            ->with('destination',$destination)
            ->run('distance');

        return $ret['data'][0];
    }
}