<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/29
 * Time: 16:23
 */

namespace Micro\User\Middleware;


use Closure;
use Illuminate\Support\Facades\Log;
use Micro\Common\Contract\Middleware;
use Micro\User\Repo\CommUserRepo;

class CheckRecommendMiddle extends Middleware
{
    public $repo;

    public function __construct(CommUserRepo $repo)
    {
        $this->repo=$repo;
    }

    public function handle($request, Closure $next)
    {
        $request['register_type'] = '02';
        Log::info('检测推荐用户-----'.$request['recommendId']);
        if($request['recommendId']=='null'||!$request['recommendId']){
            $login_name = config('const_user.RECOMMEND');
            $userInfo = $this->repo->getUserByLoginName($login_name);
            $request['recommendId'] = $userInfo['user_id'];
            $request['register_type'] = '01';
        }else{
            $this->repo->getUser($request['recommendId']);
        }
        Log::info('检测推荐用户-----'.$request['recommendId']);
        return $next($request);
    }
}