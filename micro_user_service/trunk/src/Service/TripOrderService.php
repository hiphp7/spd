<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/21
 * Time: 13:52
 */

namespace Micro\User\Service;


use Micro\Common\Base\BaseService;

class TripOrderService extends BaseService
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    /**
     * @desc 获取行程订单信息
     * @param $request
     * @return mixed
     */
    public function getTripOrderInfo($request){
        $ret = app('micro-client')
            ->micro('GetTripOrderListService')
            ->service('Micro\OrderDispatch\Service\GetTripOrderListService')
            ->with('order_id',$request['order_id'])
            ->with('user_id',$request['user_id'])
            ->run('getTripOrderInfo');
        $ret=$ret['data'];

        if($ret['order_type'] == '0030'){//顺风车订单
            $freeRideData = app('micro-client')
                ->micro('FreeRideOrderService')
                ->service('Micro\Trip\Service\FreeRideOrderService')
                ->with('order_id',$request['order_id'])
                ->run('getOrderInfoByOrderId');
            $freeRideData = $freeRideData['data'];
            $ret['people_number'] = $freeRideData['people_number'];
            $ret['ride_type'] = $freeRideData['ride_type'];
        }
        $user_info = app('micro-client')
            ->micro('DriverInfoService')
            ->service('Micro\Driver\Service\DriverInfoService')
            ->with('user_id',$ret['passenger_id'])
            ->run('getUserInfo');
        $user_info=$user_info['data'];
        $ret['passenger_name']=$user_info['user_name'];
        $ret['passenger_headimgurl']=$user_info['headimgurl'];
        $driver_info = app('micro-client')
            ->micro('DriverInfoService')
            ->service('Micro\Driver\Service\DriverInfoService')
            ->with('driver_id',$ret['driver_id'])
            ->run('getDriverInfo');
        $driver_info=$driver_info['data'];

        $vehicle_info = app('micro-client')
            ->micro('DriverInfoService')
            ->service('Micro\\Driver\\Service\\VehicleInfoService')
            ->with('driver_id',$ret['driver_id'])
            ->run('getBaseVehiclenInfo');
        $vehicle_info = $vehicle_info['data'];

        $ret['driver_name']=$driver_info['driver_name'];
        $ret['driver_headimgurl']=R($driver_info['headimgurl']);

        $params['orderData'] = $ret;
        $params['driver_info']['driver_name'] = $driver_info['driver_name'];
        $params['driver_info']['driver_phone'] = $driver_info['driver_phone'];
//        $params['headimgurl'] = $driver_info['headimgurl'];
        $params['driver_info']['vehicle_no'] = $vehicle_info['vehicle_no'];
        $params['driver_info']['brand'] = $vehicle_info['brand'];
        $params['driver_info']['vehicle_type'] = $vehicle_info['vehicle_type'];
        $params['driver_info']['vehicle_color'] = $vehicle_info['vehicle_color'];
        $params['driver_info']['car_img'] = R('webimg/carimg/car1.png');//根据车辆品牌/型号 获取车辆图片
        $params['driver_info']['order_quantity'] = 25;  //接单量
        return $params;
    }
}