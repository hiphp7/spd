<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/8
 * Time: 11:34
 */
namespace Micro\Push\Controller;
use Micro\Common\Contract\Controller;

class IndexController extends Controller
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public function index()
    {
        return $this->testSocketPushPassenger();
    }

    //测试socket消息推送
    public function testSocketPushPassenger(){
        return app('micro-client')
            ->micro('Push')
            ->with('passenger_id','1142889689825594113')
            ->with('type',config('socket.msg_type.dispatch.trip_end'))//送达乘客目的地,订单结束
            ->job('SocketPushPassengerJob')
            ->run();
    }
}