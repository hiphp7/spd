<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/30
 * Time: 15:46
 */

namespace Micro\User\Middleware;


use Closure;
use Illuminate\Support\Facades\Log;
use Micro\Common\Contract\Middleware;
use Micro\User\Repo\CommUserRepo;
use Micro\User\Repo\MessageRepo;

class getChatContentMiddle extends Middleware
{
    public $repo;
    public $userRepo;

    public function __construct(MessageRepo $repo,CommUserRepo $userRepo)
    {
        $this->repo=$repo;
        $this->userRepo=$userRepo;
    }

    public function handle($request, Closure $next)
    {
        Log::info('获取聊天记录');
        if(empty($request['last_time'])){
            $ret=$this->repo->getListByOrderId($request['page'],$request['pageSize'],$request['order_id']);
        }else{
            $ret=$this->repo->getListByOrderIdTime($request['page'],$request['pageSize'],$request['order_id'],$request['last_time']);
        }
        if(!empty($ret)){
            foreach ($ret as $key => $val){
                if($val['from_id']==$request['user_id']){
                    $ret[$key]['position']='2';//右
                    $from_info=$this->userRepo->getUser($val['from_id']);
                    $ret[$key]['user_name']=$from_info['user_name'];
                    $ret[$key]['headimgurl']=$from_info['headimgurl'];
                }
                if($val['to_id']==$request['user_id']){
                    $ret[$key]['position']='1';//左
                    $from_info=$this->userRepo->getUser($val['from_id']);
                    $ret[$key]['user_name']=$from_info['user_name'];
                    $ret[$key]['headimgurl']=$from_info['headimgurl'];
                }
            }
        }
        return $ret;
    }
}