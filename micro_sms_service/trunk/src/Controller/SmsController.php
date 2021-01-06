<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/28
 * Time: 17:26
 */
namespace Micro\Sms\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Micro\Common\Base\Base;
use Micro\Common\Contract\Controller;
use Micro\Sms\Service\SmsService;

/**
 * @desc 短信服务
 * Class SmsController
 * @package Micro\Sms\Controller
 */
class SmsController extends Controller
{
    public function getRules()
    {
        return [
            'sendSms' => [
                'mobile'      => 'required|mobile|desc:手机号',
            ],
            'checkSmsCode' => [
                'mobile'      => 'required|mobile|desc:手机号',
                'code'        => 'required|desc:验证码',
            ]
        ];
    }

    /**
     * @desc 发送短信
     * @param Request $request
     * @return mixed
     */
    public function sendSms(Request $request){
        Log::info('发送短信 |'.$request->input('mobile'));

        return Base::service(SmsService::class)
            ->with('mobile',$request->input('mobile'))
            ->with('param',config('const_sms.USER_SMS_TEMPLATE'))
            ->run('sendSms');
    }

    /**
     * @desc 短信验证
     * @param Request $request
     * @return mixed
     */
    public function checkSmsCode(Request $request){
        return Base::service(SmsService::class)
            ->with('mobile',$request->input('mobile'))
            ->with('code',$request->input('code'))
            ->run('checkSmsCode');
    }
}