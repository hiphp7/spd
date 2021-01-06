<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/9/9
 * Time: 13:50
 */

namespace Micro\Common\Protobuf\Client;

use Micro\Common\Protobuf\Service\Request;

class GrpcStub extends \Grpc\BaseStub{


    public function __construct($hostname) {
        $opts['credentials'] = \Grpc\ChannelCredentials::createInsecure();
        parent::__construct($hostname, $opts, null);
    }

    public function Handle($name,Request $argument,$metadata=[],$options=[])
    {
        $method = "/protobuf.".$name."/Handle";
        return $this->_simpleRequest(
            $method,
            $argument,
            ['\Micro\Common\Protobuf\Service\Response','decode'],
            $metadata,$options
        );
    }
}