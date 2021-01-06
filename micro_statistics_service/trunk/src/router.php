<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/30
 * Time: 16:36
 */

$router->group([],function()use ($router){

    //今日拒单数量 通过Lua脚本Redis中获取
    $router->post('/statistics/refusalOrder', 'StatisticsController@refusalOrder');
    //获取车主当日数据
    $router->post('/statistics/getDriverStatisticsDate', 'StatisticsController@getDriverStatisticsDate');

});