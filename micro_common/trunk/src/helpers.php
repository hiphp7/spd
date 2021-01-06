<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/6/27
 * Time: 18:18
 */


use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

if(! function_exists('Err')){
    function Err($message , $code=9999){
        \Illuminate\Support\Facades\Log::error("Err::".$message.", code::".$code);
        if(Str::contains($message,":")){
            list($message,$code) =explode(':',$message);
        }else{
            $code = config('const_response.'.$message.'.code',$code);
            $message = config('const_response.'.$message.'.msg',$message);
        }
        if(is_array($message) || is_object($message)){
            $message = json_encode($message);
        }

        if(DB::transactionLevel()){
            DB::rollback();
        }
        throw  new Exception($message , $code);
    }
}

if(! function_exists('Token')){
    function Token(){
        return app()->make(Micro\Common\Jwt\Token::class);
    }
}


if(! function_exists('ID')){
    function ID (){
        return app()->make(Micro\Common\Tool\IdWorker::class)->getId();
    }
}

if(! function_exists('Money')){

    function Money(){
        return app()->make(Micro\Common\Tool\Money::class);
    }

}

if(! function_exists('LOCK')){
    function LOCK($key,$expire=5) {
        $ret = app('redis')->setnx($key,time()+$expire);
        if($ret)
            return true;
        else{
            $timer = app('redis')->get($key);
            if($timer < time()){ //过期
                app('redis')->del($key);
                return LOCK($key,$expire);
            }
        }
    }
}

if(! function_exists('UNLOCK')){
    function UNLOCK($key) {
        app('redis')->del($key);
    }
}

if(! function_exists('R')) {
    function R($path = null,$flag = true)
    {
        //如果字符串中包含http:// 头像为全路径,不需要拼接
        if(strpos($path,'http://') !== false || strpos($path,'https://') !== false) return $path;

        if(app()->runningInConsole()){
            $url = env('PATH_URL')."image/";
            return $url. $path;
        }
        if($flag){
            $url = "http://" . $_SERVER['HTTP_HOST'] . "/Data" . "/";
        }else{
            $url = env('PATH_URL')."image/";
        }

        if (is_array($path)) {
            $src = array();
            foreach ($path as $key => $val) {
                $src[$key] = $url . $val;
            }
            return $src;
        }

        return $url . $path;
    }
}

/**
 * @desc 米转千米(公里)
 */
if(! function_exists('MetreToKilometer')) {
    function MetreToKilometer($metre){
        $kilometer = $metre / 1000;
        return round($kilometer,2);
    }
}

/**
 * @desc 毫秒转秒
 */
if(! function_exists('MillisecondToSecond')) {
    function MillisecondToSecond($millisecond){
        $second = $millisecond / 1000;
        return round($second,3);
    }
}

/**
 * @desc 获取毫秒级别的时间戳
 */
if(! function_exists('getMsecTime')) {
    function getMsecTime(){
        list($msec, $sec) = explode(' ', microtime());
        $msectime =  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        return $msectime;
    }
}

/**
 * @desc 毫秒转日期
 */
if(! function_exists('getMsecToMescdate')) {
    function getMsecToMescdate($msectime){
        $msectime = $msectime * 0.001;
        if(strstr($msectime,'.')){
            sprintf("%01.3f",$msectime);
            list($usec, $sec) = explode(".",$msectime);
            $sec = str_pad($sec,3,"0",STR_PAD_RIGHT);
        }else{
            $usec = $msectime;
            $sec = "000";
        }
        $date = date("Y-m-d H:i:s.x",$usec);
        return $mescdate = str_replace('x', $sec, $date);
    }
}

/**
 * @desc 日期转毫秒
 */
if(! function_exists('getDateToMesc')) {
    function getDateToMesc($mescdate){
        list($usec, $sec) = explode(".", $mescdate);
        $date = strtotime($usec);
        $return_data = str_pad($date.$sec,13,"0",STR_PAD_RIGHT);
        return $msectime = $return_data;
    }
}

if(! function_exists('getConnections')) {
    function getConnections($val){
        $redisManager = app("redis");

        $name = "redis_db_".$val['name'];

        if (isset($redisManager->connections[$name])){
            return $redisManager->connections[$name];
        }else{

            $option = [
                'host' => env('REDIS_HOST', '127.0.0.1'),
                'password' => env('REDIS_PASSWORD', null),
                'port' => env('REDIS_PORT', 6379),
                'database' => $val['database'],
                'read_timeout' =>-1,
            ];
            $connector = new \Illuminate\Redis\Connectors\PhpRedisConnector;

            return  $redisManager->connections[$name] = $connector->connect($option,[]);
        }
    }
}

/* 手机号 * 处理 */
if(! function_exists('Mobile')){
    function Mobile($value){
        $prefix = substr($value,0,3);
        //截取身份证号后4位
        $suffix = substr($value,-4,4);

        return $prefix."****".$suffix;
    }
}


if(!function_exists('reslove')){
    function reslove(){

    }
}

if(! function_exists('projectConfig')){
    function projectConfig($file_name){
        $project_name = env('PROJECT_NAME');
        $data = config($project_name.'/'.$file_name);

        //从对应项目中获取配置文件,如果没有 从默认配置文件中读取
        if(!$data) return config($file_name);

        return $data;
    }
}

/* 手机号 * 处理 */
if(! function_exists('Mobile')){
    function Mobile($value){
        $prefix = substr($value,0,3);
        //截取身份证号后4位
        $suffix = substr($value,-4,4);

        return $prefix."****".$suffix;
    }
}
//资源路径
if(! function_exists('R')) {
    function R($path = null,$flag = true)
    {
        //如果字符串中包含http:// 头像为全路径,不需要拼接
        if(strpos($path,'http://') !== false || strpos($path,'https://') !== false) return $path;

        if(app()->runningInConsole()){
            $url = env('PATH_URL')."image/";
            return $url. $path;
        }
        if($flag){
            $url = "http://" . $_SERVER['HTTP_HOST'] . "/Data" . "/";
        }else{
            $url = env('PATH_URL')."image/";
        }

        if (is_array($path)) {
            $src = array();
            foreach ($path as $key => $val) {
                $src[$key] = $url . $val;
            }
            return $src;
        }

        return $url . $path;
    }
}
/** 名字*处理 */
if(! function_exists('C')){
    function C($str){
        $length = mb_strlen($str,'UTF8');
        if($length<=0)  return '*';

        $first = mb_substr($str,0,1,'utf-8') . '*';
        $last  = '';
        if($length >= 3) {
            $last  = mb_substr($str, -1, 1,'utf-8');
        }

        return $first . $last;
    }
}
/** 地址处理 */
if(!function_exists('handleAddress')){
    function handleAddress($address){
        preg_match('/(.*?(省|自治区|北京市|天津市))/', $address, $matches);
        if (count($matches) > 1) {
            $province = $matches[count($matches) - 2];
            $address = str_replace($province, '', $address);
        }
        preg_match('/(.*?(市|自治州|地区|区划|县))/', $address, $matches);
        if (count($matches) > 1) {
            $city = $matches[count($matches) - 2];
            $address = str_replace($city, '', $address);
        }
        preg_match('/(.*?(区|县|镇|乡|街道))/', $address, $matches);
        if (count($matches) > 1) {
            $area = $matches[count($matches) - 2];
            $address = str_replace($area, '', $address);
        }

        return [
            'province' => isset($province) ? $province : '',
            'city' => isset($city) ? $city : '',
            'area' => isset($area) ? $area : '',
        ];
    }
}
