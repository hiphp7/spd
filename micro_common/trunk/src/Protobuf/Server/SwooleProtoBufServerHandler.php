<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/7/25
 * Time: 16:05
 */
namespace Micro\Common\Protobuf\Server;

use Micro\Common\Base\Base;
use Micro\Common\Protobuf\Service\Request;
use Micro\Common\Protobuf\Service\Response;


class SwooleProtoBufServerHandler
{


    public function getClientRequest($data)
    {
        try{
            $request = new Request();
            $request->mergeFromString($data);
            return $request;
        }catch (\Exception $e){
            Err('报文接收错误');
        }

    }

    public function getResponsePacked($result)
    {
        $response = new Response();
        $response->setCode($result['code']);
        $response->setMsg($result['msg']);
        $response->setData(json_encode($result['data']));
        $packed = $response->serializeToString();
        return $packed;
    }

    public function handle($param)
    {
        try{
            $ret =  Base::service($param['service'])
                ->pass($param['request'])
                ->before($param['before'])
                ->after($param['after'])
                ->middle($param['middle'])
                ->job($param['job'])
                ->broadcast($param['broadcast'])
                ->run($param['method']);
            return [
                'code' =>'0000',
                'data' => $ret,
                'msg' =>'请求成功'
            ];
        }catch (\Exception $ex){
            return [
                'code' => (String)$ex->getCode(),
                'data' =>[],
                'msg' =>$ex->getMessage()
            ];
        }

    }
    

}