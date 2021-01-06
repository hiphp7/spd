<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/8
 * Time: 11:56
 */
namespace Micro\Push\Job\Socket;
use Illuminate\Support\Facades\Log;
use Jaeger\GHttp;
use Micro\Common\Base\Base;
use Micro\Common\Contract\Job;

/**
 * @desc socket 推送
 * Class SocketPushJob
 * @package Micro\Push\Job\Socket
 */
class SocketPushPassengerJob extends Job
{
    public function handle(){
        $request = $this->request;
        Log::info('socket 推送 乘客');

        $params = isset($request['data']) ? $request['data'] : [];

        $fd = $this->getPassengerInfo($request['passenger_id']);
        $params['fd'] = $fd;
        $params['type'] = $request['type'];

        GHttp::post(config('socket.url'),$params);
        return $request;
    }

    /**
     * @desc 获取乘客信息 fd
     * @param $passenger_id
     * @return mixed
     */
    public function getPassengerInfo($passenger_id){
        $code = Base::code('redis_db.passenger');

        $redis = getConnections([
            'name' => 'default_'.$code['property2'],
            'database' => $code['property2']
        ]);

        $passengerData = $redis->hgetall(env('QUEUE_NAME')."_passenger_online:".$passenger_id);

        return $passengerData['fd'];
    }
}