<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/9/9
 * Time: 14:09
 */

namespace Micro\Common\Protobuf\Client;


use Micro\Common\Contract\RpcClient;

class GrpcClient extends RpcClient
{

    public function send($param)
    {

        $packed = $this->getPacked($param);
        $client = $this->getClient();
        $result = $client->Handle($this->serviceName,$packed)->wait();
        list($response, $status) = $result;

        if($status->code != 0){
            $ret = [
                'code' =>(string)$status->code,
                'data'=>[],
                'message'=>$status->details
            ];
        }else{
            $ret = [
                'code' =>$response->getCode(),
                'data'=>json_decode($response->getData(),true),
                'message'=>$response->getMsg()
            ];
        }
        return $ret;

    }

    public function getClient()
    {
        
        $server = $this->getMicroServer();
        $client = new GrpcStub($server);
        return $client;
    }

    public function getMicroServer()
    {
        //根据 this->microName
        $server = "127.0.0.1:11111";
        return $server;
    }
    
    public function getPacked($param)
    {
        $request= new \Micro\Common\Protobuf\Service\Request();
        $request->setId(config('REQUEST_ID',''));
        $request->setParam($param);
        return $request;
    }


}