<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/2/22
 * Time: 10:02
 */

namespace Micro\Common\Common\Service;


use App\Modules\Base\BaseService;

class TaskService
{
    public $service;
    public $request;
    public $middle;
    public $after;
    public $before;


    public function service($service=null)
    {
        $this->service = $service?$service:BaseService::class;
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

    public function middle($middle)
    {
        $this->middle = $middle;
        return $this;
    }

    public function after($after)
    {
        $this->after = $after;
        return $this;
    }

    public function before($before)
    {
        $this->before = $before;
        return $this;
    }
    public function run()
    {
        $data= [
            'service' =>$this->service,
            'request' =>$this->request,
            'middle'  =>$this->middle,
            'after'   =>$this->after,
            'before'  =>$this->before
        ];
        app('swoole')->serv->task(json_encode($data));
    }

}