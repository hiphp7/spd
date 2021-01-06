<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/8/5
 * Time: 13:41
 */

namespace Micro\Common\Base;


use Illuminate\Broadcasting\Channel;
use Micro\Common\Base\Repository\BaseBroadcastChannelRepo;
use Micro\Common\Contract\Broadcast;

class BaseBroadcast extends Broadcast
{
    public $service='broadcast';

    public function broadcastOn()
    {
        $channels = app(BaseBroadcastChannelRepo::class)->getChannels($this->service);
        $ret = [];
        foreach ($channels as $channel){
            $ret[] = new Channel($channel['channel_micro']);
        }
        return $ret;
    }

    //广播的事件名称
    public function broadcastAs()
    {
        //这个可以定位为 服务名称 service name//处理类
        return $this->service;
    }
}