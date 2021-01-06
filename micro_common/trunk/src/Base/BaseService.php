<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/1/7
 * Time: 13:30
 */

namespace Micro\Common\Base;


use Micro\Common\Base\Repository\BaseBroadcastRepo;
use Micro\Common\Base\Repository\BaseEventRepo;
use Micro\Common\Base\Repository\BaseJobRepo;
use Micro\Common\Base\Repository\BaseMiddlewareRepo;
use Illuminate\Support\Facades\Log;
use Micro\Common\Contract\Service;

class BaseService extends Service
{
    public function getRules()
    {
    }

    public function handle($request)
    {
        Log::info("BaseService");
        return $request;
    }

    public function middle($middle = null)
    {
        //Log::info("BaseService.middle");
        if(!$middle) return $this;
        $list = app(BaseMiddlewareRepo::class)->getMiddleware($middle);
        $ret = [];
        foreach($list as $k=>$v){
            $ret[$k] =$v['bean'];
        }
        return $this->setMiddleware($ret);
    }

    public function preMiddle($middle = null)
    {
        if(!$middle) return $this;
        $list = app(BaseMiddlewareRepo::class)->getMiddleware($middle);
        $ret = [];
        foreach($list as $k=>$v){
            $ret[$k] =$v['bean'];
        }
        return $this->setPreMiddleware($ret);
    }

    public function redisMiddle($middle = null){
        if(!$middle) return $this;
        $redis=app('redis')->connection('middle');
        if(!$redis->exists($middle)){
            $list = app(BaseMiddlewareRepo::class)->getMiddleware($middle);
            $ret = [];
            foreach($list as $k=>$v){
                $ret[$k] =$v['bean'];
            }
            $redis->set($middle,json_encode($ret));
        }
        $res=$redis->get($middle);
        $ret=json_decode($res,true);
        $redis->close();
        Log::info(json_encode($ret));
        return $this->setMiddleware($ret);
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

    public function job($job = null)
    {
        if(!$job) return $this;
        $list = app(BaseJobRepo::class)->getJob($job);
        $ret = [];
        foreach($list as $k=>$v){
            $ret[$k] =$v['bean'];
        }
        return $this->setJob($ret);
    }

    public function broadcast($broadcast = null)
    {
        if(!$broadcast) return $this;
        $list = app(BaseBroadcastRepo::class)->getBroadcast($broadcast);
        $ret = [];
        foreach($list as $k=>$v){
            $ret[$k] =$v['bean'];
        }
        return $this->setBroadcast($ret);
    }



    public function listenEvent($event)
    {
        app('events')->listen($event['bean'],$event['listener']);
    }
}