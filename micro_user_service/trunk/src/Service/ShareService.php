<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/12/4
 * Time: 13:22
 */

namespace Micro\User\Service;


use Illuminate\Support\Facades\Log;
use Micro\Common\Base\BaseService;
use Micro\User\Repo\CommCodeMasterRepo;

class ShareService extends BaseService
{
    public function getRules()
    {

    }

    //获取注册分享信息
    public function getShareInfo($request) {
        switch ($request['kind_of']) {
            case '0010':return $this->getUserRegisterShareInfo($request);
            case '0020':return $this->getDriverRegisterShareInfo($request);
            case '0030':return $this->getFreeRideOrderData($request);
            default:return $this->getUserRegisterShareInfo($request);
        }
    }

    //获取注册分享信息
    public function getUserRegisterShareInfo($request) {
        Log::info('注册分享信息 |' . $request['user_id']);

        $ret = projectConfig('const_share.User');

//        $ret['url'] = $ret['url'].$request['user_id'];
        $request['info'] = $ret;
        $request['open_id'] = '';
        return $ret;
    }

    //获取注册分享信息
    public function getDriverRegisterShareInfo($request) {
        Log::info('注册分享信息 |' . $request['user_id']);

        $ret = projectConfig('const_share.Driver');

//        $ret['url'] = $ret['url'].$request['user_id'];
        $request['info'] = $ret;
        $request['open_id'] = '';
        return $ret;
    }
    //0030城际顺风车分享
    public function getFreeRideOrderData($request){
        Log::info('城际顺风车分享 |' . $request['user_id']);

        $data = app('micro-client')
            ->micro('FreeRideOrderService')
            ->service('Micro\\Trip\\Service\\FreeRideOrderService')
            ->with('order_id',$request['id'])
            ->run('getOrderInfoById');
        if(!$data['data']) Err('行程不存在!');
        if($data['data']['state'] != '0000') Err('行程已取消,不可预约!');

        $logoArr = config('const_share.FreeRideOrderLogo');

        $data = $data['data'];
        if($data['state'] != '0000') Err('订单已取消,不可分享!');
        $driver_info = app('micro-client')
            ->micro('DriverInfoService')
            ->service('Micro\Driver\Service\DriverInfoService')
            ->with('driver_id',$data['driver_id'])
            ->run('getDriverInfo');

        $driver_name = isset($driver_info['data']['driver_name']) ? $driver_info['data']['driver_name'] : '';

        $dep_area = handleAddress($data['dep_are']);

        $ret['title'] = '顺风车 '.$dep_area['city'] . $dep_area['area'].'->'.$data['dest_city'].' '.$data['dest_area'];
        $ret['content'] = '出发时间: '.$data['departure_date'].'左右出发,'.$dep_area['city'] . $dep_area['area'].'->'.$data['dest_city'].' '.$data['dest_area'].',同城可接送!';
        $ret['logo'] = 'https://apps.liugeche.cn/'.$logoArr[array_rand($logoArr)];
        $ret['url'] = config('const_share.URL.free_ride_order').$request['id'];
        return $ret;
    }

    //二次分享数据
    public function webShare(CommCodeMasterRepo $repo,$request) {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $timestamp = time();
        $noncestr = $this->getRandString();
        // 检测文件是否存在
        $json = $this->checkFile(time());
        $arr = array(
            'noncestr' => $noncestr,
            'jsapi_ticket' => $json['jsapi_ticket'],
            'timestamp' => $timestamp,
            'url' => $request['url'],
        );
        Log::debug('makeSign' . json_encode($arr));
        //从配置数据中获取而日新配置
        $master = $repo->getConfigure('wxconfig_public','wx');

        $ret = array(
            'appId' => $master['property2'],
            'timestamp' => $timestamp,
            'nonceStr' => $noncestr,
            'signature' => $this->makeSign($arr),
        );
        return $ret;
    }

    /**
     * 生成签名
     * @param $params
     * @return string 签名
     */
    public function makeSign($params) {
        //签名步骤一：按字典序排序数组参数
        ksort($params);
        $string = $this->ToUrlParams($params);
        Log::debug('$string========' . $string);
        //签名步骤二：对string1进行sha1签名，得到signature
        $signature = sha1($string);
        return $signature;
    }

    /**
     * 将参数拼接为url: key=value&key=value
     * @param   $params
     * @return  string
     */
    public function ToUrlParams($params) {
        $string = '';
        if (!empty($params)) {
            $array = array();
            foreach ($params as $key => $value) {
                $array[] = $key . '=' . $value;
            }
            $string = implode("&", $array);
        }
        return $string;
    }

    /**
     * 产生一个指定长度的随机字符串
     * @param int $len 产生字符串的长度
     * @return string 随机字符串
     */
    public function getRandString($len = 32) {
        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9",
        );

        $charsLen = count($chars) - 1;
        // 将数组打乱
        shuffle($chars);
        $ret = "";
        for ($i = 0; $i < $len; $i++) {
            $ret .= $chars[mt_rand(0, $charsLen)];
        }
        Log::debug('$ret' . $ret);
        return $ret;
    }

    public function checkFile($timestamp)
    {
        // 检测文件是否存在
        $check = file_exists(config('parameter.SHARE.root'));

        if ($check == false) {
            $reToken = $this->getToken();
            $jsapi_ticket = $reToken['ticket'];
            $access_token = $reToken['access_token'];
            $json = array(
                'expire_time' => $timestamp,
                'jsapi_ticket' => $jsapi_ticket,
                'access_token' => $reToken['access_token'],
            );
            Log::info('缓存Token文件不存在，生成新文件' . json_encode($json));
            $this->fileWrite(json_encode($json));
        }

        $json = json_decode($this->fileRead(), true);

        if ($timestamp < $json['expire_time'] + 6000) {
            $json = $this->fileRead();
            Log::info('缓存有效' . $json);
            $json = json_decode($json, true);
            $access_token = $json['access_token'];
        } else {
            $reToken = $this->getToken();
            $jsapi_ticket = $reToken['ticket'];
            $access_token = $reToken['access_token'];
            $json = array(
                'expire_time' => $timestamp,
                'jsapi_ticket' => $jsapi_ticket,
                'access_token' => $reToken['access_token'],
            );
            Log::info('缓存Token已过期，重新请求Token，新旧Token五分钟内同时有效' . json_encode($json));
            $this->fileWrite(json_encode($json));
        }
        return $json;
    }

    /**
     * 获取Token
     * @return mixed
     */
    public function getToken() {
        $codeMaster = $this->master->getConfigure('wxconfig_public','wx');
        if(!$codeMaster) Err('分享失败!');
        $urlToken = projectConfig('parameter.SHARE.tokenUrl') . '&appid=' . $codeMaster['property2'] . '&secret=' . $codeMaster['property3'];
        Log::debug('$urlToken===' . $urlToken);
        $ret_token = $this->httpGet($urlToken);
        $access_token = $ret_token['access_token'];
        $urlTicket = projectConfig('parameter.SHARE.ticketUrl') . $access_token;
        Log::debug('$urlTicket=====' . $urlTicket);
        $ret_ticket = $this->httpGet($urlTicket);
        $re['access_token'] = $access_token;
        $re['ticket'] = $ret_ticket['ticket'];
        return $re;
    }

    public function fileWrite($json) {
        $file = fopen(config('parameter.SHARE.root'), "w");
        fwrite($file, $json);
        fclose($file);
        return $json;
    }

    public function fileRead() {
        $file = fopen(config('parameter.SHARE.root'), "r");
        $json = fread($file, filesize(config('parameter.SHARE.root')));
        fclose($file);
        return $json;
    }

    /**
     * GET请求
     * @param $url
     * @return mixed
     */
    public function httpGet($url) {
        // 1. 初始化
        $curl = curl_init();
        // 2. 设置选项，包括URL
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        // 3. 执行并获取HTML文档内容
        $ret = curl_exec($curl);
        if ($ret === FALSE) {
            Err('请求失败，请重试', '7777');
        }
        // 4. 释放curl句柄
        curl_close($curl);
        Log::info('Token' . $ret);
        return json_decode($ret, true);
    }

    /**
     * @param 判断远程图片是否存在
     */
    public function img_exits($url){
        $header  = @get_headers($url, true);

        if(isset($header[0]) && (strpos($header[0], '200') || strpos($header[0], '304'))){
            return true;
        }else{
            return false;
        }
    }
}
