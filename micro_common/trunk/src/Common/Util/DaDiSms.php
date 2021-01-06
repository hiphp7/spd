<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/18
 * Time: 16:19
 */

namespace Micro\Common\Common\Util;
use Illuminate\Support\Facades\Log;

/**
 * @desc 大地短信通道
 * Class DaDiSms
 * @package Micro\Common\Common\Util
 */
class DaDiSms
{
    /**
     * @param $request
     * @param array $request['mobile'] 手机号
     * @param array $request['message'] 发送内容
     */
    public function handle($request){
        $username = projectConfig('parameter.SMS_INFO.Name');

        //帐户名密码,取MD5大写取32位 密码,网址：http://md5jiami.51240.com/

        $password = strtoupper(md5(projectConfig('parameter.SMS_INFO.Psw')));

        //网关ID
        $gwid = projectConfig('parameter.SMS_INFO.Id');

        $url = projectConfig('parameter.SMS_INFO.url');

        $data = array
        (
            'type' => 'send',
            'username' => $username,
            'password' => $password,
            'gwid' => $gwid,
            'mobile' => $request['mobile'],
            'message' => $request['message']
        );

        $this->postSMS($url,$data);
    }

    public function postSMS($url,$data){
        $headers = array('Accept' => 'text/xml');
        $ret = \Unirest\Request::get($url, $headers, $data);

        try{
            $ret = xmlToData($ret->raw_body);

            if($ret['code'] != 0){
                Log::info('失败原因 '.json_encode($ret,JSON_UNESCAPED_UNICODE));
                Err('短信发送失败');
            }
            return $ret['code'];
        }catch (\Exception $e){
            Err('短信发送失败,请稍后再试');
        }
    }
}