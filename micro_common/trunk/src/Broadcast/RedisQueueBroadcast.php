<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/8/2
 * Time: 16:15
 */
namespace Micro\Common\Broadcast;

use Illuminate\Broadcasting\Broadcasters\RedisBroadcaster;
use Illuminate\Support\Facades\Log;

class RedisQueueBroadcast extends RedisBroadcaster
{
    public function broadcast(array $channels, $event, array $payload = [])
    {
        foreach ($this->formatChannels($channels) as $channel) {
            try{
                $ret = app('micro-client')
                    ->micro($channel)
                    ->service($event)
                    ->pass($payload['request'])
                    ->run();
            }catch (\Exception $ex){
                Log::info($ex->getMessage());
            }

        }
    }
}