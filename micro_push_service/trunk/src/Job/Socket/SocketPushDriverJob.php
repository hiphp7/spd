<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/21
 * Time: 15:20
 */

namespace Micro\Push\Job\Socket;


use Illuminate\Support\Facades\Log;
use Jaeger\GHttp;
use Micro\Common\Contract\Job;

class SocketPushDriverJob extends Job
{
    public function handle(){
        $request = $this->request;
        Log::info('socket 推送 司机');

        $params = isset($request['data']) ? $request['data'] : [];

        $params['fd'] = $request['fd'];
        $params['type'] = $request['type'];

        GHttp::post(config('socket.url'),$params);
        return $request;
    }
}