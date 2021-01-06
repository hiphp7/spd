<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/8/2
 * Time: 10:42
 */

namespace Micro\Common\Protobuf\Server;


use SwooleTW\Http\Server\Manager;

class SwooleTcpServerManager extends Manager
{
    protected $events = [
        'start',
        'shutDown',
        'workerStart',
        'workerStop',
        'packet',
        'bufferFull',
        'bufferEmpty',
        'task',
        'finish',
        'pipeMessage',
        'workerError',
        'managerStart',
        'managerStop',
        'receive'
    ];

    public function onRequest($swooleRequest, $swooleResponse){}

    public function onReceive($server, $fd, $from_id, $data)
    {
        $this->app->make('events')->dispatch('swoole.receive',func_get_args());
        $handler = $this->app->make(SwooleProtoBufServerHandler::class);
        $request = $handler->getClientRequest($data);
        config(['REQUEST_ID'=>$request->getId()]);
        $result = $handler->handle(json_decode($request->getParam(),true));
        $packed = $handler->getResponsePacked($result);
        $server->send($fd,$packed);
    }

}