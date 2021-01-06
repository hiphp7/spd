<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/7/17
 * Time: 08:56
 */

namespace Micro\Common\Thrift\Server;

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Server\TServer;

class SwooleThriftSocketServer extends TServer
{

    function onStart()
    {
        //todo 启动时 注册到redis,服务名称和地址,
        echo "Swoole Thrift Server Start\n";
    }
    function notice($log)
    {
        echo $log."\n";
    }

    public function onReceive($serv, $fd, $from_id, $data)
    {
        $transport = new SwooleTFramedTransport();
        $transport->setHandle($fd);
        $transport->buffer = $data;
        $transport->server = $serv;
        $protocol = new TBinaryProtocol($transport, false, false);

        try {
            $this->processor_->process($protocol, $protocol);
        } catch (\Exception $e) {
            $this->notice('CODE:' . $e->getCode() . ' MESSAGE:' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }


    function serve()
    {
        $serv = new \swoole_server('127.0.0.1', 8091);
        $serv->on('workerStart', [$this, 'onStart']);
        $serv->on('receive', [$this, 'onReceive']);
        //todo swoole_server 更多设置
        $serv->set(array(
            'worker_num'            => 1,
            'dispatch_mode'         => 1, //1: 轮循, 3: 争抢
            'open_length_check'     => true, //打开包长检测
            'package_max_length'    => 8192000, //最大的请求包长度,8M
            'package_length_type'   => 'N', //长度的类型，参见PHP的pack函数
            'package_length_offset' => 0,   //第N个字节是包长度的值
            'package_body_offset'   => 4,   //从第几个字节计算长度
        ));
        $serv->start();
    }

    public function stop()
    {
        $this->transport_->close();
    }


}