<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/8
 * Time: 11:33
 */
$router->group([],function()use ($router){
    //获取行程订单列表（用户） 分为历史行程订单和行程中订单
    $router->post('/push/index', ['uses' => 'IndexController@index','as' => 'index']);
    $router->post('/push/jpush', ['uses' => 'JpushController@jpush','as' => 'jpush']);

    $router->post('/push/pushDriverInterCityOrder', ['uses' => 'PmsPushController@pushDriverInterCityOrder','as' => 'pushDriverInterCityOrder']);
});