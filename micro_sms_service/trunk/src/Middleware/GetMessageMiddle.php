<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/9
 * Time: 16:37
 */

namespace Micro\Sms\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Micro\Common\Contract\Middleware;
use Micro\Sms\Repository\CommSmsRepo;
use Micro\Sms\Repository\CommPushTempletRepo;

/**
 * @desc 获取短信模版
 * Class GetMessageMiddle
 * @package Micro\Sms\Middleware
 */
class GetMessageMiddle extends Middleware
{
    public $sms;
    public $temp;
    public $code = '';
    public function __construct(CommSmsRepo $sms,CommPushTempletRepo $temp)
    {
        $this->sms = $sms;
        $this->temp = $temp;
    }

    public function handle($request, Closure $next)
    {
        Log::info('获取短信模版');
        $templet = $this->getSmsTemplet($request['param']['code']);


        $pattern = '/[{](.*?)[}]/';
        preg_match_all($pattern,$templet['content'],$matches);

        $res = [];
        foreach($matches[1] as $key =>$value){

            $value = trim($value);
            $funName = "get".$value;
            $res[$value]=$this->$funName($request);
        }

        foreach($res as $k=>$v){
            $pattern = '{'. $k .'}';
            $templet = str_replace($pattern,$v,$templet);
        }

        $request['message'] = $templet;
        $request['code'] = $this->code;
        return $next($request);
    }

    //获取短信模版
    public function getSmsTemplet($code)
    {
        return $this->temp->getSmsTemplet($code);
    }

    //生成随机短信
    public function getVerfiCode($request){
        $length = config('const_sms.CODELEN');
        $code = rand(pow(10,($length-1)), pow(10,$length)-1);
        $this->code = $code;
        return $code;
    }

    //过期时间
    public function getMyDate($request)
    {
        return 5;
    }

    //用户名
    public function getName($request)
    {
        $ret = $this->user->getUser($request['user_id']);
        return C($ret['user_name']);
    }

    public function getMobile($request)
    {
//        $ret = $this->user->getUserByLoginName($request['mobile']);
        return Mobile($request['mobile']);
    }

    public function getTel($request)
    {
        return $request['tel'];
    }

    public function getBookTime($request)
    {
        return $request['bookTime'];
    }

    public function getCarDesc($request)
    {
        return $request['carDesc'];
    }

    public function getCustom($request)
    {
        return $request['custom'];
    }

    public function getNumber($request)
    {
        return $request['number'];
    }

    //获取起点
    public function getDepCity($request){
        return $request['data']['dep_city'] ?? '';
    }
    //获取终点
    public function getDestCity($request){
        if($request['data']['dest_city'])
            return $request['data']['dest_city'].' '.$request['data']['dest_area'];
        else '';
    }


}