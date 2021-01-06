<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/30
 * Time: 15:44
 */

namespace Micro\User\Middleware;


use Closure;
use Illuminate\Support\Facades\Log;
use Micro\Common\Contract\Middleware;
use Micro\User\Repo\MessageRepo;

class CheckGetTimeMiddle extends Middleware
{
    public $repo;

    public function __construct(MessageRepo $repo)
    {
        $this->repo=$repo;
    }

    public function handle($request, Closure $next)
    {
        Log::info('获取时间');
        $redis = app('redis')->connection('default');
//            $redis->select(3);
        $last_time = $redis->get('chat-lasttime:order_id-'.$request['order_id'].'-user_id'.$request['user_id']);
        if(!empty($last_time)){
            $request['last_time']=date('Y-m-d H:i:s',$last_time);
        }else{
            $request['last_time']='';
            $redis->set('chat-lasttime:order_id-'.$request['order_id'].'-user_id'.$request['user_id'],time());
        }

        $this->repo->UpdateByOrderID($request['order_id'],['read_status'=>'0000']);

        return $next($request);
    }
}