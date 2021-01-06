<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/7/17
 * Time: 15:19
 */

namespace Micro\Common\Thrift\Client;
use Thrift\Exception\TException;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Protocol\TMultiplexedProtocol;
use Thrift\Transport\TFramedTransport;
use Thrift\Transport\TSocket;

class ThriftClient
{
    // 配置文件
    public $config = [];
    public $request = [];
    public $handler = null;
    public function __construct()
    {
        $config = [
            'host' => '127.0.0.1',
            'port' => '8091'
        ];
        $this->config = $config;
    }

    public function send($request)
    {
        try {
            $socket = new TSocket($this->config['host'], $this->config['port']);
            $socket->setRecvTimeout(50000);
            $socket->setDebug(true);
            $transport = new TFramedTransport($socket, true,true);
            $transport->open();
            $protocol = new TBinaryProtocol($transport);
            $thriftProtocol = new TMultiplexedProtocol($protocol, 'RemoteService');
            $handler =  new ClientHandler($thriftProtocol);
            $result = $handler->handle(json_encode($request));
            $result->data = json_decode($result->data, true);
            $transport->close();
            return $result;
        } catch (TException $ex) {
            app('log')->error('服务连接失败 ', ['host' => $this->config, 'content' => $ex->getMessage()]);
            return ['host' => $this->config,  'content' => $ex->getMessage()];
        }
    }

}