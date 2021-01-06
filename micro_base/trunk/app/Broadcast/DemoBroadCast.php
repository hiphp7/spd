<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/8/5
 * Time: 14:03
 */

namespace App\Broadcast;


use Micro\Common\Base\BaseBroadcast;

class DemoBroadCast extends BaseBroadcast
{
    public $service = "demoservice";

    //判定事件是否广播
    public function broadcastWhen()
    {
        return true;
    }

}