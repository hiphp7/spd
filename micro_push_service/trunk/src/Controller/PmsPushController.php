<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/25
 * Time: 15:57
 */

namespace Micro\Push\Controller;


use Illuminate\Http\Request;
use Micro\Common\Base\Base;
use Micro\Common\Contract\Controller;

class PmsPushController extends Controller
{
    public function getRules()
    {
        return [
            'pushDriverInterCityOrder' => [
                'id' => 'required|desc:城际待派订单ID',
                'driver_id' => 'required|desc:司机ID'
            ]
        ];
    }

    /**
     * @desc 推送司机城际订单
     * @param Request $request
     * @return mixed
     */
    public function pushDriverInterCityOrder(Request $request){
        //1.获取订单详情
        $ret = app('micro-client')
            ->micro('CostCalculationService')
            ->service('Micro\Trip\Service\IntercityWaitDispatchOrderService')
            ->with('order_id',$request->input('id'))   //乘客上车时间
            ->run('getIntercityWaitDispatchOrder');
        $data = $ret['data'];
//        if($data['state'] != '0010') Err('当前订单不可派遣!');
        $userInfo = app('micro-client')
            ->micro('DriverInfoService')
            ->service('Micro\\User\\Service\\MineService')
            ->with('user_id',$data['passenger_id'])
            ->run('getMineInfo');
        $userInfo = $userInfo['data'];

        $last_num = substr($userInfo['phone_number'],-4);

        $code = Base::code('redis_db.driver_related');
        $redis = getConnections([
            'name' => 'default_'.$code['property2'],
            'database' => $code['property2']
        ]);

        $redis_order_data = $redis->hgetall(env('QUEUE_NAME')."_driver_no_trip:".$request['driver_id']);
        if(!$redis_order_data) Err('司机未上线,请提醒上线!');
        $data['last_num'] = $last_num;
        $data['order_id'] = $data['id'];
        return app('micro-client')
            ->micro('Push')
            ->with('fd',$redis_order_data['fd'])
            ->with('data',$data)
            ->with('type',config('socket.msg_type.order.inter_city_order'))//城际订单 0022
            ->job('SocketPushDriverJob')
            ->run();
    }
}