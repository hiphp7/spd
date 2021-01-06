<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/7/25
 * Time: 16:22
 */
namespace Micro\Common\Protobuf\Client;
use GuzzleHttp\Client;
use Micro\Common\Contract\RpcClient;

class ProtobufClient extends RpcClient
{
    public function send($param)
    {
        $packed = $this->getPacked($param);
        $client = $this->getClient();
        $client->send($packed);
        $response = $client->recv();
        $client->close();
        $ret = new \Micro\Common\Protobuf\Service\Response();
        try{
            $ret->mergeFromString($response);
            return [
                'code' => $ret->getCode(),
                'data' =>json_decode($ret->getData(),true),
                'msg' =>$ret->getMsg()
            ];
        }catch (\Exception $ex){
            return  [
                'code' => (String)$ex->getCode(),
                'data' =>[],
                'msg' =>'报文解析错误'
            ];
        }

    }

    public function getPacked($param)
    {
        $request= new \Micro\Common\Protobuf\Service\Request();
        $request->setId(config('REQUEST_ID',''));
        $request->setParam($param);
        return $request->serializeToString();

    }

    public function getClient()
    {
        $server = $this->getMicroServerFromConsul();
        $client = new \swoole_client(SWOOLE_SOCK_TCP);
        if (!$client->connect($server['Address'], $server['Port'], -1))
        {
            Err("connect failed. Error: {$client->errCode}\n");
        }

        return $client;
    }
    public function getMicroServerFromConsul()
    {
        $server=[];
        try {
            $consulUrl = config('base.register') . "/v1/health/service/" . $this->microName;
            $httpClient = new Client();
            $content = $httpClient->get($consulUrl)->getBody()->getContents();
            foreach (json_decode($content) as $key=>$item){
                foreach ($item->Checks as $k=>$check){
                    if($check->ServiceName == $this->microName && $check->Status=='passing'){
                        $server[$key]['ServiceName'] = $this->microName;
                        $server[$key]['Address'] = $item->Service->Address;
                        $server[$key]['Port'] =  $item->Service->Port;
                    }
                }
            }
        }catch (\Exception $e){
            Err("获取".$this->microName."失败");
        }
        if(empty($server)){
            Err('服务不可用');
        }
        return $server[array_rand($server)];

    }

}