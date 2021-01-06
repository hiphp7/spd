<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/29
 * Time: 14:46
 */

namespace Micro\User\Controller;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Micro\Common\Base\Base;
use Micro\Common\Contract\Controller;
use Micro\User\Middleware\CheckRegistrationStatusMiddle;

class LoginContriller extends Controller
{
    public function getRules()
    {
        return [
            'codeLogin'=>[
                'mobile'      => 'required|desc:手机号|regex:/^1[34578][0-9]{9}$/',//|regex:/^1[34578][0-9]{9}$/
                'code' => 'required|desc:验证码|min:4',
            ]
        ];
    }
    /**
     * @desc 手机验证码登陆;存在登陆;不存在注册,登陆
     * @param Request $request
     */
    public function codeLogin(Request $request)
    {
        Log::info('乘客端登录');
        $mobile = $request->input('mobile');
        $code = $request->input('code');
        $check_sms=app('micro-client')
            ->service('Micro\Sms\Service\SmsService')
            ->micro('SmsService')
            ->with('mobile',$mobile)
            ->with('code',$code)
            ->run('checkSmsCode');
        if($check_sms['code']!='0000'){
            Err($check_sms['msg']);
        }
        $check=Base::service()
            ->with('mobile',$mobile)
            ->with('code',$code)
            ->setMiddleware([
                CheckRegistrationStatusMiddle::class,
            ])
            ->run();
        if($check['status']=='0020'){
            //已注册用户无需注册直接登录
            //登录
            $ret=app('micro-client')
                ->service('Micro\User\Service\LoginService')
                ->micro('aaa')
                ->with('mobile',$mobile)
                ->with('code',$code)
                ->with('recommendId','')
                ->with('project_code', '10')
                ->run('doLoginProcess');
        }else{
            //未注册用户先注册
            //第一次注册账户
            $ret=app('micro-client')
                ->service('Micro\User\Service\LoginService')
                ->micro('aaa')
                ->with('mobile',$mobile)
                ->with('code',$code)
                ->with('recommendId','')
                ->with('project_code', '10')
                ->run('doLoginProcessFirst');
        }

        return $ret;
    }
}