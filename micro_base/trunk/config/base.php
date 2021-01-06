<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/8/1
 * Time: 15:35
 */
return [
    'service' =>env('RPC_SERVICE','DEFAULT'),
    'service_id' => env('RPC_SERVICE_ID','1'),
    'service_driver' => 'base',
    'protocol' =>[
        'base' => Micro\Common\Base\BaseClient::class,
        'proto' => Micro\Common\Protobuf\Client\ProtobufClient::class
    ],
    'host' => env('RPC_HOST','127.0.0.1'),
    'register' => env('RPC_REGISTER','http://127.0.0.1:8500'),
];