<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/3
 * Time: 11:14
 */

namespace Micro\Push\Controller;


use Illuminate\Http\Request;
use Micro\Common\Base\Base;
use Micro\Common\Contract\Controller;
use Micro\Push\Service\JPushService;

class JpushController extends Controller
{
    public function getRules()
    {

    }

    public function jpush(Request $request){
        //【麦田商旅】您的手机验证码 ： {VerfiCode}， {MyDate}分钟内有效，请尽快填写完成验证。为保障您的账户安全，请勿外泄。
        return Base::service(JPushService::class)
            ->with('target','driver_1311370310523587328')
            ->with('param',config('const_sms.msg_type.dispatch.trip_end'))
            ->with('data','')
            ->run('jpush');
    }
}