<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/8/1
 * Time: 15:41
 */

namespace Micro\Common\Base;


use Micro\Common\Contract\RpcClient;

class BaseClient extends RpcClient
{

    public function send($param)
    {
        $data = json_decode($param,true);
        try{
            $response = Base::service($data['service'])
                ->pass($data['request'])
                ->before($data['before'])
                ->after($data['after'])
                ->middle($data['middle'])
                ->job($data['job'])
                ->broadcast($data['broadcast'])
                ->run($data['method']);
            $ret= [
                'code' =>'0000',
                'data' => $response,
                'msg' =>'请求成功'
            ];

        }catch (\Exception $ex){
            $ret = [
                'code' => (String)$ex->getCode(),
                'data' =>[],
                'msg' =>$ex->getMessage()
            ];
        }
        return $ret;
    }


}