<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/26
 * Time: 15:52
 */

namespace Micro\Common\Contract;

use GuzzleHttp\Client;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Container\BoundMethod;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Redis;

abstract class Service
{

    public $request;
    public $middleware = [];
    //前置中间件
    public $preMiddle = [];
    public $beforeEvent = [];
    public $afterEvent = [];
    public $job = [];
    public $channel;
    public $host;
    public $notifyListName;
    public $redisConnection;
    public $broadcast = [];
    public $microName;


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

    public function run($method = 'handle')
    {
        $this->beforeRun($method);
        $this->request = $this->runPipeline($method);
        $this->afterRun($method);
        return $this->request;
    }

    public function runTransaction($method = 'handle')
    {
        $this->beforeRun($method);
        DB::beginTransaction();
        $this->request  = $this->runPipeline($method);
        try{
            DB::commit();
        }catch (\Exception $e){
            DB::rollback();
        }
        $this->afterRun($method);
        return $this->request;
    }

    public function runPost($url,$method = 'handle')
    {
        $this->request = $this->runPrePipeline();
        $this->request['http_response'] = $this->httpPostRequest($url,$this->request);
        return $this->run($method);
    }
    public function runGet($url,$method = 'handle')
    {
        $this->request = $this->runPrePipeline();
        $this->request['http_response'] = $this->httpGetRequest($url,$this->request);
        return $this->run($method);
    }
    public function runPostTransaction($url,$method = 'handle')
    {
        $this->request = $this->runPrePipeline();
        $this->request['http_response'] = $this->httpPostRequest($url,$this->request);
        return $this->runTransaction($method);
    }
    public function runGetTransaction($url,$method = 'handle')
    {
        $this->request = $this->runPrePipeline();
        $this->request['http_response'] = $this->httpGetRequest($url,$this->request);
        return $this->runTransaction($method);
    }


    private function beforeRun($method){
        $this->validateService($method);
        $this->beforeEventFire($this->request,$method);
    }
    private function afterRun($method){
        $this->afterEventFire($this->request,$method);
        $this->dispatchJob($this->request,$method);
        $this->publishChannel();
        $this->pushNotify();
        $this->sendBroadcasts();
    }

    private function runPipeline($method)
    {
        return  (new Pipeline(app()))
            ->send($this->request)
            ->through($this->getMiddleware($method))
            ->then($this->process($this, $method));
    }

    public function runPrePipeline()
    {
        return  (new Pipeline(app()))
            ->send($this->request)
            ->through($this->preMiddle)
            ->then($this->process($this, 'buildHttpRequest'));
    }

    protected function pushNotify()
    {
        $notifyListName = $this->getNotify();
        if($notifyListName){
            $connection = $this->redisConnection ?? 'default';
            $message = $this->request['notify_message'] ?? $this->request;
            $notifyName = "notify_list_".$notifyListName;
            app('redis')->connection($connection)->rpush($notifyName,json_encode($message));
        }
    }


    
    private function httpPostRequest($url,$request)
    {
        $client = new Client();
        $host = $this->host ?? config('app.api_gateway');
        $uri = $host.$url;
        $form_params = $request['http_request'] ?? $request;
        $resp = $client->post($uri,['form_params'=>$form_params]);
        $ret = json_decode($resp->getBody()->getContents(),true);
        return $ret;
    }

    private function httpGetRequest($url,$request)
    {
        $client = new Client();
        $host = $this->host ?? config('app.api_gateway');
        $uri = $host.$url;
        $query = $request['http_request'] ?? $request;
        $resp = $client->get($uri,['query'=>$query]);
        $ret = json_decode($resp->getBody()->getContents(),true);
        return $ret;
    }

    public function getMiddleware($method)
    {
        $baseMiddleware = $this->getMiddle($method);
        $configMiddleware = $this->getConfigMiddleware($method);
        $baseMiddleware = $baseMiddleware ? $baseMiddleware :[];
        $configMiddleware = $configMiddleware ? $configMiddleware :[];
        return array_merge($baseMiddleware, $configMiddleware);
    }


    public function process($class, $method)
    {
        return function ($request) use ($class, $method) {
            $method = get_called_class()."@".$method;
            return BoundMethod::call(app(),$method,[$request]);
            //  return call_user_func([$class, $method], $request);
        };
    }

    public function validateService($method)
    {
        if (!method_exists($this, $method))
            throw new \ReflectionException(get_called_class() . "->" . $method . "方法没找到");
        $rules = $this->getRules();
        if (isset($rules[$method]))
            app('validator')->validate($this->request, $rules[$method], [], []);

    }
    public function micro($micro)
    {
        $this->microName = $micro ;
        return $this;
    }


    //订阅通道设置
    public function channel(String $channel)
    {
        $this->channel = $channel;
        return $this;
    }
    //消息通知设置
    public function notify(String $notifyListName, String $redisConnection='')
    {
        $this->notifyListName = $notifyListName;
        $this->redisConnection = $redisConnection;
        return $this;
    }
    //curl 请求主机设置
    public function setHost($host){
        $this->host = $host;
        return $this;
    }

    //设置前置中间件
    public function setPreMiddleware($preMiddle =[])
    {
        if(is_array($preMiddle)){
            $this->preMiddle = $preMiddle;
        }
        return $this;
    }

    //设置中间件
    public function setMiddleware($middleware=[])
    {
        if(is_array($middleware)){
            $this->middleware = $middleware;
        }
        return $this;
    }
    //设置事后任务
    public function setJob(Array $job=[])
    {
        if(is_array($job)){
            $this->job = $job;
        }
        return $this;
    }

    //设置事前时事件
    public function setBeforeEvent($event=[])
    {
        if(is_array($event)){
            $this->beforeEvent = $event;
        }
        return $this;
    }

    //设置事后事件
    public function setAfterEvent($event=[])
    {
        if(is_array($event)){
            $this->afterEvent = $event;
        }
        return $this;
    }


    public function setBroadcast($broadcast = [])
    {
        if(is_array($broadcast)){
            $this->broadcast = $broadcast;
        }
        return $this;
    }

    public function getBroadcast()
    {
        return $this->broadcast;
    }

    /**
     * 批量发送事前事件
     * @param $request
     * @param $method
     */
    private function beforeEventFire($request,$method)
    {
        $beforeEvent = $this->getBefore($method);
        foreach ($beforeEvent as $event) {
            $body = $this->getEventBody($request,'before');
            $this->eventFire($event,$body);
        }
    }




    /**
     * 批量发送事后事件
     * @param $request
     * @param $method
     */
    private function afterEventFire($request,$method)
    {
        $afterEvent = $this->getAfter($method);
        Log::info(json_encode($afterEvent));
        foreach ($afterEvent as $event) {
            $body = $this->getEventBody($request,'after');
            Log::info("批量发送事后事件");
            //Log::info(json_encode($body));
            //Log::info(json_encode($event));
            $this->eventFire($event,$body);
        }
    }

    private function dispatchJob($request,$method)
    {
        $jobs = $this->getJob($method);
        Log::info(json_encode($jobs));
        foreach ($jobs as $job){
            $this->dispatch(new $job($request));
        }
    }

    private function dispatch($job)
    {
        return app('Illuminate\Contracts\Bus\Dispatcher')->dispatch($job);
    }

    //消息发布
    private function publishChannel()
    {
        $channel = $this->getChannel();
        if($channel){
            $message = $this->request['publish_subscribe_message'] ?? $this->request ;
            Redis::publish($this->channel,json_encode($message));
        }
    }


    /**
     * 单个发送事件
     * @param $event
     * @param $request
     */
    private function eventFire($event,$request)
    {
        if(is_array($request)){
            $message = $request;
            $message['event_fire_time'] = time();
        }else{
            $message['message'] = $request;
            $message['event_fire_time'] = time();
        }
        Log::info("Event Fire :".json_encode($message));
        Event::fire(new $event($message));
    }

    private function sendBroadcasts(){
        foreach ($this->getBroadcast() as $broadcast){
            $this->sendBroadcast($broadcast);
        }
    }

    private function sendBroadcast($class){

        $broadcast = new $class($this->request);
        app('events')->dispatch('broadcast',$broadcast);
    }

    /**
     * 根据数组获得方法相应的Event或者Middleware
     * @param $arrays
     * @param $method
     * @return array
     */
    private function getScopeForMethod($arrays,$method)
    {
        if($method =='handle'){
            return $arrays;
        }
        $result = [];
        foreach ($arrays as $name => $options) {
            if (isset($options['only']) && !in_array($method, (array)$options['only'])) {
                continue;
            }
            if (isset($options['except']) && in_array($method, (array)$options['except'])) {
                continue;
            }
            $result[] = $name;
        }
        return $result;
    }


    public function getConfigMiddleware($method)
    {
        return [];
    }

    public function getEventBody($request,$type)
    {
        return $request['event'][$type] ?? $request;
    }

    
    //---------------下面用于重载
    public function getNotify()
    {
        return $this->notifyListName;
    }
    public function getChannel()
    {
        return $this->channel;
    }
    public function getBefore($method)
    {
        return $this->getScopeForMethod($this->beforeEvent,$method);;
    }

    public function getAfter($method)
    {
        return $this->getScopeForMethod($this->afterEvent,$method);
    }
    public function getJob($method)
    {
        return $this->getScopeForMethod($this->job,$method);
    }

    public function getMiddle($method)
    {
        return $this->getScopeForMethod($this->middleware,$method);
    }


    abstract public function getRules();
    abstract public function handle($request);
    //预处理返回
    public function buildHttpRequest($request)
    {
        return $request;
    }


}