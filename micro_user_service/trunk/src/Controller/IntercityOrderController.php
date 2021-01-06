<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/16
 * Time: 14:56
 */

namespace Micro\User\Controller;


use Micro\Common\Base\Base;
use Micro\Common\Contract\Controller;
use Illuminate\Http\Request;

class IntercityOrderController extends Controller
{
    public function getRules(){
        return [
            'passengerCancelInterCityOrder' => [
                'order_id' => 'required|desc:城际订单id'
            ],
            'getWaitDispatchOrderData' => [
                'order_id' => 'required|desc:城际订单id'
            ],
            'arriveDestination' => [
                'order_id' => 'required|desc:城际订单id'
            ]
        ];
    }

    /**
     * @desc 乘客获取城际派送订单列表
     * @param Request $request
     * @return mixed
     * @return string id ID
     * @return string departure_date 出发日期
     * @return string dep_longitude 出发经度
     * @return string dep_latitude 出发纬度
     * @return string dep_are 出发点
     * @return string dest_longitude 目的地经度
     * @return string dest_latitude 目的地纬度
     * @return string dest_area 目的地
     * @return string pick_up_time 开始接人时间
     * @return string departure_time 预计出发时间
     * @return string unit_price 单价
     * @return string total_price 总价
     * @return string state 0000->订单完成 0010->待派单 0020->已派单(等待司机接单)  0030->重新派单(司机未接单) 0040->已接单 0050->等待司机 0055->司机到达上车点 0060->接到乘客,行程中 0070->订单取消
     */
    public function getWaitDispatchOrderList(Request $request){
        $passenger_id = $request->user()->claims->getId();

        $ret = app('micro-client')
            ->micro('CostCalculationService')
            ->service('Micro\Trip\Service\IntercityWaitDispatchOrderService')
            ->with('passenger_id',$passenger_id)   //乘客上车时间
            ->run('getWaitOrderListByPassengerId');
        return $ret['data'];
    }

    /**
     * @desc 乘客取消城际订单
     * @param Request $request
     * @return mixed
     */
    public function passengerCancelInterCityOrder(Request $request){
        $passenger_id = $request->user()->claims->getId();

        $ret = app('micro-client')
            ->micro('IntercityWaitDispatchOrderService')
            ->service('Micro\Trip\Service\IntercityWaitDispatchOrderService')
            ->with('passenger_id',$passenger_id)
            ->with('order_id',$request->input('order_id'))
            ->run('cancelInterCityOrder');

        return $ret['data'];
    }

    /**
     * @desc 获取城际等待派送订单信息
     * @param Request $request
     * @return mixed
     */
    public function getWaitDispatchOrderData(Request $request){
        $passenger_id = $request->user()->claims->getId();

        $ret = app('micro-client')
            ->micro('IntercityWaitDispatchOrderService')
            ->service('Micro\Trip\Service\IntercityWaitDispatchOrderService')
            ->with('passenger_id',$passenger_id)
            ->with('order_id',$request->input('order_id'))
            ->run('getWaitDispatchOrderData');

        return $ret['data'];
    }

    /**
     * @desc 城际乘客到达目的地
     * @param Request $request
     * @return mixed
     */
    public function arriveDestination(Request $request)
    {
        $passenger_id = $request->user()->claims->getId();

        $ret = app('micro-client')
            ->micro('IntercityWaitDispatchOrderService')
            ->service('Micro\Trip\Service\IntercityWaitDispatchOrderService')
            ->with('passenger_id',$passenger_id)
            ->with('order_id',$request->input('order_id'))
            ->run('arriveDestination');

        return $ret['data'];
    }
}