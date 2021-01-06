<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/18
 * Time: 14:03
 */


return [

    'env' => env('APP_ENV'), //development 开发  production 生产
    'app_id' =>env('APP_ID',"DEFAULT_001"),
    'debug' => env('APP_DEBUG', false),

    'locale' => 'cn',

    'timezone' => 'PRC',

    'token_key' => 'EHKHHP54PXKYTS2E',
    'token_exp' => '2592000',

    'log' => 'daily',

    'APP_PATH' => './public',

    'server_id' =>env('SERVER_ID',1),

    'providers' => [
        Iwanli\Wxxcx\WxxcxServiceProvider::class,
        Jenssegers\Mongodb\MongodbServiceProvider::class,

//        Laravel\Scout\ScoutServiceProvider::class,
//        Baijunyao\LaravelScoutElasticsearch\ElasticsearchServiceProvider::class,

    ],
    'aliases' => [
        'Moloquent' => Jenssegers\Mongodb\Eloquent\Model::class,
        'Mongo'     => Jenssegers\Mongodb\MongodbServiceProvider::class,
    ],
    'KEY' =>env('APP_KEY'),
    //sql 日志打印
    'sql_log' => env('SQL_LOG',true),

    //
];