<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/3
 * Time: 9:56
 */
$router->group([],function()use ($router){
    $router->post('/user/codeLogin', ['uses' => 'LoginContriller@codeLogin','as' => 'codeLogin']);
    $router->post('/user/getMessageList', ['uses' => 'SystemInfoController@getMessageList','as' => 'getMessageList']);
    $router->post('/user/getMsgContent', ['uses' => 'SystemInfoController@getMsgContent','as' => 'getMsgContent']);
    $router->post('/user/getChatList', ['uses' => 'SystemInfoController@getChatList','as' => 'getChatList']);
    $router->post('/user/getChatContent', ['uses' => 'SystemInfoController@getChatContent','as' => 'getChatContent']);
    $router->post('/user/sendMessage', ['uses' => 'SystemInfoController@sendMessage','as' => 'sendMessage']);
    $router->post('/user/getMineInfo', ['uses' => 'MineController@getMineInfo','as' => 'getMineInfo']);
    $router->post('/user/uploadHeadImg', ['uses' => 'MineController@uploadHeadImg','as' => 'uploadHeadImg']);
    $router->post('/user/modifyNickname', ['uses' => 'MineController@modifyNickname','as' => 'modifyNickname']);
    $router->post('/user/modifyGender', ['uses' => 'MineController@modifyGender','as' => 'modifyGender']);
    $router->post('/user/modifyAge', ['uses' => 'MineController@modifyAge','as' => 'modifyAge']);
    $router->post('/user/modifyBirthday', ['uses' => 'MineController@modifyBirthday','as' => 'modifyBirthday']);
    $router->post('/user/modifyLoginName', ['uses' => 'MineController@modifyLoginName','as' => 'modifyLoginName']);
    $router->post('/user/modifyIndustry', ['uses' => 'MineController@modifyIndustry','as' => 'modifyIndustry']);

    $router->post('/user/getTripOrderInfo', ['uses' => 'MineController@getTripOrderInfo','as' => 'getTripOrderInfo']);
    $router->post('/user/createEmergencyContact', ['uses' => 'MineController@createEmergencyContact','as' => 'createEmergencyContact']);
    $router->post('/user/getEmergencyList', ['uses' => 'MineController@getEmergencyList','as' => 'getEmergencyList']);
    $router->post('/user/getEmergencyInfo', ['uses' => 'MineController@getEmergencyInfo','as' => 'getEmergencyInfo']);
    $router->post('/user/delEmergency', ['uses' => 'MineController@delEmergency','as' => 'delEmergency']);
    $router->post('/user/updateEmergency', ['uses' => 'MineController@updateEmergency','as' => 'updateEmergency']);
    $router->post('/user/getIndustryCategory', ['uses' => 'MineController@getIndustryCategory','as' => 'getIndustryCategory']);
    $router->post('/user/submitFeeback', ['uses' => 'MineController@submitFeeback','as' => 'submitFeeback']);
    $router->post('/user/getComProblem', ['uses' => 'MineController@getComProblem','as' => 'getComProblem']);
    $router->post('/user/getBalance', ['uses' => 'MineController@getBalance','as' => 'getBalance']);
    $router->post('/user/getBillInfo', ['uses' => 'MineController@getBillInfo','as' => 'getBillInfo']);
    $router->post('/system/getAreaBankList', ['uses' => 'SystemInfoController@getAreaBankList','as' => 'getAreaBankList']);
    $router->post('/system/getSupportBankList', ['uses' => 'SystemInfoController@getSupportBankList','as' => 'getSupportBankList']);
    $router->post('/system/getProvinceList', ['uses' => 'SystemInfoController@getProvinceList','as' => 'getProvinceList']);
    $router->post('/system/getCityList', ['uses' => 'SystemInfoController@getCityList','as' => 'getCityList']);
    $router->post('/share/getShareInfo', ['uses' => 'ShareController@getShareInfo','as' => 'getShareInfo']);
    $router->post('/share/webShare', ['uses' => 'ShareController@webShare','as' => 'webShare']);
});
$router->group(['auth'],function()use ($router){
    //获取行程订单列表（用户） 分为历史行程订单和行程中订单
    $router->post('/user/getTripOrderList', ['uses' => 'MineController@getTripOrderList','as' => 'getTripOrderList']);
    //获取乘客获取行程中订单
    $router->post('/user/getOnTripOrderByPassengerId', ['uses' => 'PassengerTripOrderController@getOnTripOrderByPassengerId','as' => 'getOnTripOrderByPassengerId']);
    $router->post('/user/generateCode', ['uses' => 'UserController@generateCode','as' => 'generateCode']);
    $router->post('/user/bindBankCard', ['uses' => 'MineController@bindBankCard','as' => 'bindBankCard']);

    //获取等待派单列表
    $router->post('user/getWaitDispatchOrderList', ['uses' => 'IntercityOrderController@getWaitDispatchOrderList']);
    //乘客取消城际订单
    $router->post('user/passengerCancelInterCityOrder', ['uses' => 'IntercityOrderController@passengerCancelInterCityOrder']);
    //获取城际等待派送订单信息
    $router->post('user/getWaitDispatchOrderData', ['uses' => 'IntercityOrderController@getWaitDispatchOrderData']);

    //城际乘客到达目的地
    $router->post('user/arriveDestination', ['uses' => 'IntercityOrderController@arriveDestination']);




});