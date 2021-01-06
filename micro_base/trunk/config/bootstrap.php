<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/7/1
 * Time: 12:01
 */


return [

    'middleware' =>[
        App\Middleware\RequestId::class,
        //后置的报文响应中间件
        //允许跨域中间件
        App\Middleware\CORSMiddleware::class,
        //重组报文中间件
        App\Middleware\Response::class,
        App\Middleware\MockService::class

    ],

    'routeMiddleware' => [
        'auth' => App\Middleware\Authenticate::class, //token 验证
        'permission' => App\Middleware\Permission::class, //权限验证
        'verify' => App\Middleware\VerifyData::class, //数据验证
        'restrict' =>\App\Middleware\Restrictions::class, //根据token限制登陆
        'parallelredis' =>\App\Middleware\Parallel::class,//操作频繁控制
        'noaction' =>\App\Middleware\NoAction::class, //禁止操作,
        'cache' => App\Middleware\CacheResponse::class
    ],
    'service_provider' =>[
        App\Providers\JsonLogServiceProvider::class,
        App\Providers\RoutesServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        \Illuminate\Redis\RedisServiceProvider::class,
        \App\Providers\VersionServiceProvider::class,


        //调用者可用


        Micro\Common\Provider\BaseProvider::class,
        //扩展redis广播
        Micro\Common\Provider\ExtendBroadcastProvider::class,


        //swoole http server 与 tcp rpc server 不可同时使用
        SwooleTW\Http\LumenServiceProvider::class,
        // swoole ProtoBuf Rpc server
//        Micro\Common\Provider\SwooleProtoBufServerProvider::class,

        Micro\Common\Provider\MicroClientProvider::class,
        Micro\Statistics\StatisticsProvider::class,
        Micro\User\UserProvider::class,
        Micro\Push\PushProvider::class,
//        Micro\Sms\SmsProvider::class,


    ]

];