<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/22
 * Time: 9:53
 */

namespace App\Middleware;
use App\Modules\Access\Repository\CommUserRepo;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Log;

/**
 * Class Restrictions
 * @package App\Middleware
 * @desc 根据token限制只能有一台客户端操作
 */
class Restrictions
{
    public function __construct(CommUserRepo $user)
    {
        $this->user = $user;
    }

    public function handle(Request $request , Closure $next)
    {
        $token = $request->user()->token;
        $user_id = $request->user()->claims->getId();
        $userInfo = $this->user->find($user_id,['token']);
        Log::info('验证token '.$token);
        if(empty($userInfo['token'])){
            $update = [
                'token' => $token
            ];
            $this->user->updateUser($user_id,$update);
        }else if($userInfo['token'] != $token){
            Err('请重新登陆!');
        }
        return $next($request);
    }
}