<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/29
 * Time: 14:36
 */
namespace Micro\Sms\Service;
use Illuminate\Support\Facades\Log;
use Micro\Common\Base\Base;
use Micro\Common\Base\BaseService;

class SmsService extends BaseService
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    /**
     * @desc 发送短信
     * @param $request
     * @return mixed
     */
    public function sendSms($request){
        Log::info('发送短信 |'.$request['mobile']);

        $param  = $request['param'];   //发送短信模版code,类型

        return Base::service()
            ->with('mobile',$request['mobile'])
            ->with('param',$param)
            ->with('data',$request['data'] ?? [])
            ->middle('SendSms')
            ->run();
    }

    /**
     * @desc 验证短信
     * @param $request
     * @return mixed
     */
    public function checkSmsCode($request){
        return Base::service()
            ->with('mobile',$request['mobile'])
            ->with('code',$request['code'])
            ->middle('CheckSmsCode')
            ->run();
    }

}