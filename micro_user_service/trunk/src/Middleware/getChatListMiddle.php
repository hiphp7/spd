<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/30
 * Time: 14:31
 */

namespace Micro\User\Middleware;


use Closure;
use Illuminate\Support\Facades\Log;
use Micro\Common\Contract\Middleware;
use Micro\Common\Criteria\Criteria;
use Micro\User\Repo\CommChatRepo;
use Micro\User\Repo\CommUserRepo;
use Micro\User\Repo\MessageRepo;

class getChatListMiddle extends Middleware
{
    public $repo;
    public $userRepo;
    public $messageRepo;

    public function __construct(CommChatRepo $repo,CommUserRepo $userRepo,MessageRepo $messageRepo)
    {
        $this->repo=$repo;
        $this->userRepo=$userRepo;
        $this->messageRepo=$messageRepo;
    }

    public function handle($request, Closure $next)
    {
        Log::info('获取消息列表');
        $ret = $this->repo->getListByPassengerId(
            $request['user_id'],
            $request['page'],
            $request['pageSize']
        );

        if(!empty($ret)){
            foreach ($ret as $key => $val){
                $user_info=$this->userRepo->getUser($val['driver_id']);
                $ret[$key]['user_name']=$user_info['user_name'];
                $ret[$key]['headimgurl']=$user_info['headimgurl'];
                $criteria=Criteria::create()
                    ->where('order_id','=',$val['order_id'])
                    ->where('from_id','=',$val['driver_id'])
                    ->where('to_id','=',$val['user_id'])
                    ->where('read_status','=','0010');
                $ret[$key]['unread']=$this->messageRepo->count($criteria);
                $ret[$key]['last_message']=$this->messageRepo->find($val['message_id'],['content'])['content'];
            }
        }

        return $ret;
    }
}