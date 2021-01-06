<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/28
 * Time: 17:36
 */
namespace Micro\Sms\Middleware;
use Closure;
use Illuminate\Support\Facades\Log;
use Micro\Common\Contract\Middleware;
use Micro\Sms\Repository\CommSmsRepo;
use Micro\Sms\Repository\CommPushTempletRepo;

/**
 * @desc 获取短信模版
 * Class GetSmsTempletMiddle
 * @package Micro\Sms\Middleware
 */
class SendSmsMiddle extends Middleware
{
    public $sms;
    public $temp;
    public function __construct(CommSmsRepo $sms,CommPushTempletRepo $temp)
    {
        $this->sms = $sms;
        $this->temp = $temp;
    }

    public function handle($request, Closure $next)
    {
        //检查同一手机号一天内短信发送次数
        $this->checkSmsNumber($request['mobile']);
        $smsData = config('parameter.SMS_INFO');

        $data = array(
            'Id'        =>$smsData['Id'],
            'Name'      =>$smsData['Name'],
            'Psw'       =>$smsData['Psw'],
            'Message'   =>$request['message']['content'],
            'Phone'     =>$request['mobile'],
            'Timestamp' =>'0'
        );

        $headers = array('Accept' => 'text/xml');

        $rs = \Unirest\Request::get($smsData['url'], $headers, $data);

        $rs->code;        // HTTP Status code
        $rs->headers;     // Headers
        $rs->body;        // Parsed body
        $rs->raw_body;    // Unparsed body

        if($rs->code != 200){
            Err('CAPTCHA_FILE');  //短信发送失败
        }

        $result = array();
        $rs = explode(',', $rs->body);

        foreach ($rs as $value) {
            $arr = explode(':', $value);
            $result[$arr[0]] = $arr[1];
        }

        $arr = array(
            'id'            => ID(),
            'mobile'        => $request['mobile'],
            'captcha'       => $request['code'],
            'create_time'   => date('Y-m-d H:i:s'),
            'business_code' => $request['param']['code'],
        );

        $this->sms->insert($arr);

        return $next($result);
    }

    //检查用户一天发送短信次数
    public function checkSmsNumber($mobile)
    {
        $count = $this->sms->getCountByMobile($mobile);
        $number = config('const_sms.SMS_NUMBER');

        if($count >= $number){
            Err('当前手机号短信次数发送过多,请稍后再试!');
        }

    }
}