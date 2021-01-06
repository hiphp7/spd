<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/1/7
 * Time: 13:27
 */

namespace Micro\Common\Base;


use Micro\Common\Base\Repository\BaseMiddlewareRepo;
use Micro\Common\Base\Repository\BaseServiceRepo;
use Micro\Common\Base\Repository\CodeMasterRepo;
use Micro\Common\Base\Repository\UserCodeMasterRepo;
use Micro\Common\Contract\Module;

class BaseModule extends Module
{

    public function getListen() {}
    public function getSubscribe() {}

    public function __construct()
    {
        $this->registerEvent();
    }
    public function service($service = null){

        if($service == null){
            return app()->make(BaseService::class);
        }
        //全类名的直接返回,全命名空间的
        if(strpos($service,"\\")){
            return app()->make($service);
        }

        $record = app(BaseServiceRepo::class)->getService($service);
        if($record){
            return app()->make($record['bean']);
        }else{
            return app()->make(BaseService::class);
        }
    }

    public function registerEvent()
    {
        $events = app('events');
        $listen = $this->getListen();
        $this->listen = is_array($listen)?$listen :[];
        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                $events->listen($event, $listener);
            }
        }
        $subscribe = $this->getSubscribe();
        $this->subscribe = is_array($subscribe) ? $subscribe :[];
        foreach ($this->subscribe as $subscriber) {
            $events->subscribe($subscriber);
        }
    }

    public function middleware($middle = null)
    {
        if(!$middle) return [];
        $list = app(BaseMiddlewareRepo::class)->getMiddleware($middle);
        $ret = [];
        foreach($list as $k=>$v){
            $ret[$k] =$v['bean'];
        }
        return $ret;
    }



    public function code($key = null)
    {
        if(!$key) return [];
        return app(CodeMasterRepo::class)->getCode($key);
    }

    public function userCode($user_id,$key)
    {
        if(!$key) return [];
        $userCode = app(UserCodeMasterRepo::class)->getCode($user_id,$key);
        if($userCode){
            return $userCode;
        }else{
            return $this->code($key);
        }

    }

    public function swoole($service = null)
    {
        $service = $service??BaseService::class;
        $app = app(BaseSwoole::class)
            ->service($service);
        return $app;
    }

    public function repo($model=null)
    {
        if($model)
            return new BaseRepo(app($model));
        else
            return new BaseRepo(app(BaseModel::class));

    }

}