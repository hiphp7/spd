<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/7/30
 * Time: 13:38
 */

namespace Micro\Common\Contract;


use Illuminate\Support\Facades\Log;

abstract class RpcClient
{
    public $request = [];
    public $microName = "";
    public $serviceName='BaseService';
    public $middle='';
    public $after='';
    public $before='';
    public $job='';
    public $broadcast = '';
    public $preMiddle;

    public function with($key, $value)
    {
        if($key){
            $this->request[$key] = $value;
            return $this;
        }
        return $this;
    }


    public function pass($request)
    {
        if(empty($request)) return $this;
        $this->request = $request;
        return $this;
    }

    public function service($service='BaseService')
    {
        $this->serviceName = $service ?? $this->serviceName;
        return $this;
    }

    public function preMiddle($middle)
    {
        $this->preMiddle = $middle;
        return $this;
    }

    public function micro($micro)
    {
        $this->microName = $micro ;
        return $this;
    }

    public function middle($middle)
    {
        $this->middle = $middle;
        return $this;
    }
    public function after($after){
        $this->after = $after;
        return $this;
    }

    public function before($before)
    {
        $this->before = $before;
        return $this;
    }

    public function job($job)
    {
        $this->job = $job;
        return $this;
    }
    public function broadcast($broadcast)
    {
        $this->broadcast = $broadcast;
        return $this;
    }

    public function microService($microService)
    {
        if(strpos($microService,".")){
            list($microName,$serviceName) = explode(".",$microService);
            $this->microName = $microName;
            $this->serviceName = $serviceName;
        }
        return $this;
    }


    public function run($method = 'handle')
    {
        $param = [
            'service'=>$this->serviceName,
            'method' =>$method,
            'after' =>$this->after,
            'before' =>$this->before,
            'middle' =>$this->middle,
            'job' =>$this->job,
            'broadcast'=>$this->broadcast,
            'request'=>$this->request,
        ];
        $request = json_encode($param);
        Log::info('RPC_REQUEST:'.$request);
        $response =  $this->send($request);
        Log::info('RPC_RESPONSE:'.json_encode($response));
        return $response;
    }
    abstract public function send($param);
}