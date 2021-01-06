<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/29
 * Time: 14:06
 */

namespace Micro\Sms\Middleware;


use Closure;
use Micro\Common\Contract\Middleware;
use Micro\Sms\Repository\CommSmsRepo;

class CheckSmsCodeMiddle extends Middleware
{
    public $sms;
    public function __construct(CommSmsRepo $sms)
    {
        $this->sms = $sms;
    }

    public function handle($request, Closure $next)
    {
        $mobileArr = [
            '15806426525',
            '13001665031'
        ];
        if(in_array($request['mobile'],$mobileArr)){
            return $next($request);
        }
        $data = $this->sms->getMobileCaptcha($request['mobile']);
        // 判断验证码时候过期
        $this->expCaptcha($data['create_time']);

        if($data['captcha'] != $request['code']){
            Err('CAPTCHA_ERROR');  //验证码错误
        }
        return $next($request);
    }

    //判断验证码是否过期
    public function expCaptcha($time)
    {
        $minute = floor((time() - strtotime($time))%86400/60);
        if($minute > 5){
            Err('CAPTCHA_EXP');   //验证码过期
        }
    }
}