<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/7/16
 * Time: 17:31
 */

namespace Micro\Common\Thrift\Server;


use Micro\Common\Thrift\Service\RemoteServiceIf;
use Micro\Common\Thrift\Service\Response;

class ServerServiceHandler implements RemoteServiceIf
{

    public function handle($params)
    {
        $input = json_decode($params, true);
        // 自己可以实现转发的业务逻辑
        echo $params;
        $response = new Response();
        $response->code = '0000';
        $response->msg = '成功';
        $response->data = json_encode($input);
        return $response;
    }


}