<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/26
 * Time: 15:52
 */

namespace Micro\Common\Common\Contracts;

use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Container\BoundMethod;
use Illuminate\Support\Facades\Event;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

abstract class Service
{
    use ProvidesConvenienceMethods;

    public $request;
    public $middleware = [];
    public $beforeEvent = [];
    public $afterEvent = [];
    public $job = [];

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

        $this->validateService($method);
        $this->beforeEvent($this->request,$method);
        $this->request = $this->runPipeline($method);
        $this->afterEvent($this->request,$method);
        $this->dispatchJob($this->request,$method);
        return $this->request;
    }

    public function runTransaction($method = 'handle')
    {
        $this->validateService($method);
        $this->beforeEvent($this->request,$method);
        DB::beginTransaction();
        $this->request  = $this->runPipeline($method);
        try {
            DB::commit();
        }catch (\Exception $e){
             DB::rollback();
        }
        $this->afterEvent($this->request,$method);
        $this->dispatchJob($this->request,$method);
        return $this->request;
    }

    public function runPipeline($method)
    {
        return  (new Pipeline(app()))
                ->send($this->request)
                ->through($this->getMiddleware($method))
                ->then($this->process($this, $method));

    }

    //设置中间件
    public function setMiddleware($middleware=[])
    {

        if(is_array($middleware)){
            $this->middleware = $middleware;
        }
        return $this;
    }

    public function getMiddleware($method)
    {
        $baseMiddleware = $this->getScopeForMethod($this->middleware,$method);
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

    //设置事后任务
    public function setJob($job=[])
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

    /**
     * 批量发送事前事件
     * @param $request
     * @param $method
     */
    public function beforeEvent($request,$method)
    {
        $beforeEvent = $this->getScopeForMethod($this->beforeEvent,$method);
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
    public function afterEvent($request,$method)
    {
        $afterEvent = $this->getScopeForMethod($this->afterEvent,$method);
        Log::info(json_encode($afterEvent));
        foreach ($afterEvent as $event) {
            $body = $this->getEventBody($request,'after');
            Log::info("批量发送事后事件");
            //Log::info(json_encode($body));
            //Log::info(json_encode($event));
            $this->eventFire($event,$body);
        }
    }

    public function dispatchJob($request,$method)
    {
        $jobs = $this->getScopeForMethod($this->job,$method);
        Log::info(json_encode($jobs));
        foreach ($jobs as $job){
            $this->dispatch(new $job($request));
        }
    }
    
    

    /**
     * 单个发送事件
     * @param $event
     * @param $request
     */
    public function eventFire($event,$request)
    {
        $request['event_fire_time'] = time();
        //Log::info(json_encode($request));

        Event::fire(new $event($request));
    }

    /**
     * 根据数组获得方法相应的Event或者Middleware
     * @param $arrays
     * @param $method
     * @return array
     */
    public function getScopeForMethod($arrays,$method)
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
    abstract public function getRules();

}