<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/7/16
 * Time: 17:28
 */
namespace Micro\Common\Console;

use Illuminate\Console\Command;

use Micro\Common\Thrift\Server\ServerServiceHandler;
use Micro\Common\Thrift\Server\SwooleThriftSocketServer;
use Micro\Common\Thrift\Service\RemoteServiceProcessor;
use Thrift\Exception\TException;
use Thrift\Factory\TBinaryProtocolFactory;
use Thrift\Factory\TTransportFactory;
use Thrift\Server\TServerSocket;
use Thrift\TMultiplexedProcessor;

class SwooleThriftServer extends Command
{
    protected $signature = 'swoole:thrift';
    protected $description = 'swoole thrift rpc 服务';

    public function handle()
    {
        try{
            $handler = new ServerServiceHandler();
            $processor = new RemoteServiceProcessor($handler);
            $tranport = new TServerSocket();
            $out_factory = $in_factory = new TTransportFactory();
            $out_protocol = $in_protocol = new TBinaryProtocolFactory(true, true);
            $multiProcessor = new TMultiplexedProcessor();
            // 注册服务
            $multiProcessor->registerProcessor(
                'RemoteService',
                $processor
            );
            $server = new SwooleThriftSocketServer(
                $multiProcessor,
                $tranport,
                $in_factory,
                $out_factory,
                $in_protocol,
                $out_protocol
            );
            $server->serve();

        }catch (TException $ex){
            app('log')->error('Swoole Thrift 服务启动失败', ['content' => $ex->getMessage()]);
        }
    }
}