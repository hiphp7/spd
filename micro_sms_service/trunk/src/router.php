<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/29
 * Time: 10:39
 */
$router->group([],function()use ($router){

    //手机号注册
    $router->post('/sms/sendSms', ['uses' => 'SmsController@sendSms', 'as' => '']);
    $router->post('/sms/checkSmsCode', ['uses' => 'SmsController@checkSmsCode', 'as' => '']);
});