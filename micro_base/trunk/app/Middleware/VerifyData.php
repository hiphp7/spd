<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/18
 * Time: 10:18
 */

namespace App\Middleware ;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class VerifyServiceProvider
 * @package App\Providers
 * @desc 数据验证
 */
class VerifyData
{
    /**
     * @param Request $request
     * @param Closure $next
     * @throws \Exception
     * @desc md5(base58_encode ( json( $data + md5(time()) ) ))
     */
    public function handle(Request $request , Closure $next)
    {
        return $next($request);
        if(config('app.env') == 'development'){
            return $next($request);
        }
        $data = $request->all();
        Log::info('数据验证--- '.json_encode($data));
//        $data = [
//            'goods_id' => '1215302643715576832',
//            'price_id' => '1215334460422135040',
//            'timekey' => '1545906117574',
//            'authToken' => 'undefined',
//            'timesign' => 'e0db2bc6394ded5c4837887849092d96',
//        ];
//        Log::info('------- 签名数据 ------ '.json_encode($data));

        $timesign = $data['timesign'] ?? '';
        unset($data['timesign']);
        $data['timekey'] = $data['timekey'] ?? '';

        $data['timekey'] = md5($data['timekey']);


        $jsonStr = json_encode($data);

        $base58Str = md5($this->base58_encode($jsonStr));

        if($timesign != $base58Str){
            Err('数据格式错误!');
        }
        return $next($request);
    }

    /**
     * @param $base58
     * @return bool|string
     * @desc base58 解码
     */
    public function base58_decode($base58){
        $alphabet = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
        $base = strlen($alphabet);

        if (is_string($base58) === false || !strlen($base58)) {
            return false;
        }
        //array_flip 交换数组中的键和值 , str_split 将字符串转换为数组说明
        $indexes = array_flip(str_split($alphabet));
        $chars = str_split($base58);
        //验证base58
        foreach ($chars as $char) {
            if (isset($indexes[$char]) === false) {
                return false;
            }
        }

        $decimal = $indexes[$chars[0]];

        for ($i = 1, $l = count($chars); $i < $l; ++$i) {
            //bcmod 乘以
            $decimal = bcmul($decimal, $base);
            $decimal = bcadd($decimal, $indexes[$chars[$i]]);
        }


        $output = '';
        while ($decimal > 0) {
            $byte = bcmod($decimal, 256);
            $output = pack('C', $byte).$output;
            $decimal = bcdiv($decimal, 256, 0);
        }
        return $output;
    }

    /**
     * @param $string
     * @return bool|string
     * @desc base58 编码
     */
    public function base58_encode($string)
    {
        $alphabet = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
        $base = strlen($alphabet);

        if (is_string($string) === false || !strlen($string)) {
            return false;
        }

        $bytes = array_values(unpack('C*', $string));
        $decimal = $bytes[0];
        for ($i = 1, $l = count($bytes); $i < $l; ++$i) {
            $decimal = bcmul($decimal, 256);
            $decimal = bcadd($decimal, $bytes[$i]);
        }

        $output = '';
        while ($decimal >= $base) {
            $div = bcdiv($decimal, $base, 0);
            $mod = bcmod($decimal, $base);
            $output .= $alphabet[$mod];
            $decimal = $div;
        }
        if ($decimal > 0) {
            $output .= $alphabet[$decimal];
        }
        $output = strrev($output);

        return (string) $output;
    }

    /**
     * php截取指定两个字符之间字符串，默认字符集为utf-8 Power by 大耳朵图图
     * @param string $begin  开始字符串
     * @param string $end    结束字符串
     * @param string $str    需要截取的字符串
     * @return string
     */
    public function cut($begin,$end,$str){
        $b = mb_strpos($str,$begin) + mb_strlen($begin);
        $e = mb_strpos($str,$end) - $b;

        return mb_substr($str,$b,$e);
    }


}