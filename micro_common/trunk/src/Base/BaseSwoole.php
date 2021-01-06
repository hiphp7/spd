<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/2/19
 * Time: 16:00
 */

namespace Micro\Common\Base;

use Micro\Common\Base\Repository\BaseEventRepo;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

class BaseSwoole
{
    use ProvidesConvenienceMethods;

    public $service;
    public $task = false;
    public $request =[];
    public $middle;
    public $after;
    public $job = [];


    public $beforeEvent = [];
    public $afterEvent = [];

    public function service($service)
    {
        $this->service = $service;
        return $this;
    }
    public function with($key, $value)
    {

        $this->request[$key] = $value;
        return $this;
    }
    public function pass($request)
    {
        $this->request = $request;
        return $this;
    }
    public function middle($middle){
        $this->middle = $middle;
        return $this;
    }

    public function task()
    {
        $this->task = true;
        return $this;
    }

    public function after($event = null)
    {
        if(!$event) return $this;
        $list = app(BaseEventRepo::class)->getAfterEvent($event);
        $ret = [];
        foreach($list as $k=>$v){
            $ret[$k] =$v['bean'];
            $this->listenEvent($v);
        }
        //去重事件
        $data = array_flip(array_flip($ret));
        return $this->setAfterEvent($data);

    }

    public function before($event = null)
    {
        if(!$event) return $this;
        $list = app(BaseEventRepo::class)->getBeforeEvent($event);
        $ret = [];
        foreach($list as $k=>$v){
            $ret[$k] =$v['bean'];
            $this->listenEvent($v);
        }
        //去重事件
        $data = array_flip(array_flip($ret));
        return $this->setAfterEvent($data);
    }

    public function run()
    {
        //在这里进行事前时间,事后事件的发送
        $response = [];
        try{
            $this->beforeEvent($this->request);

            $client = new \swoole_client(SWOOLE_SOCK_TCP);
            $client->connect(config('app.swoole_server'), config('app.swoole_port'), -1);
            $message = [
                'task' => $this->task,
                'service' => $this->service,
                'middle' => $this->middle,
                'request' => $this->request,
                'after' =>$this->after
            ];
            $client->send(json_encode($message));
            $response = json_decode($client->recv(),true);
            $client->close();

            $this->afterEvent($response);
            $this->dispatchJob($response);

        }catch (\Exception $ex){
            dd($ex->getTrace());
            Err("swoole链接失败");
        }

        return $response;

    }

    public function beforeEvent($request)
    {
        foreach ($this->beforeEvent as $event) {
            $body = $this->getEventBody($request,'before');
            $this->eventFire($event,$body);
        }
    }

    /**
     * 批量发送事后事件
     * @param $request
     * @param $method
     */
    public function afterEvent($request)
    {
        foreach ($this->afterEvent as $event) {
            $body = $this->getEventBody($request,'after');
            $this->eventFire($event,$body);
        }
    }
    public function dispatchJob($request)
    {
        foreach ($this->jobs as $job){
            $this->dispatch(new $job($request));
        }
    }
    public function getEventBody($request,$type)
    {
        return $request['event'][$type] ?? $request;
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
        $this->beforeEvent = $event;
        return $this;
    }

    //设置事后事件
    public function setAfterEvent($event=[])
    {
        $this->afterEvent = $event;
        return $this;
    }

    public function listenEvent($event)
    {
        app('events')->listen($event['bean'],$event['listener']);
    }

    public function eventFire($event,$request)
    {
        $request['event_fire_time'] = time();
        app('events')->fire(new $event($request));
    }
}