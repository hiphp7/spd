<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/8/2
 * Time: 18:03
 */

namespace Micro\Common\Contract;


use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

abstract class Broadcast implements ShouldBroadcast
{
    use  SerializesModels;
    public $request;
    public function __construct($request)
    {
        $this->request = $request;

    }
    public function broadcastQueue()
    {
        return 'broadcast';
    }
    //广播内容
    public function broadcastWith()
    {
        return ['request' => $this->request];
    }
}