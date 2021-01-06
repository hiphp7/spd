<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/31
 * Time: 13:54
 */

namespace Micro\User\Middleware;


use Closure;
use Micro\Common\Contract\Middleware;
use Micro\User\Repo\CommUserRepo;

class CheckToUserIdMiddle extends Middleware
{
    public $repo;

    public function __construct(CommUserRepo $repo)
    {
        $this->repo=$repo;
    }

    public function handle($request, Closure $next)
    {
        Log::info('检测会员');
        $user_info=$this->repo->userCache($request['to_id']);
        if(empty($user_info)){
            Err('接收消息用户不存在');
        }
        return $next($request);
    }
}