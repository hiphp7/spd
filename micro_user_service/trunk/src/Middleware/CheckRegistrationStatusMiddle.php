<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/29
 * Time: 15:14
 */

namespace Micro\User\Middleware;


use Closure;
use Micro\Common\Contract\Middleware;
use Micro\Common\Criteria\Criteria;
use Micro\User\Repo\CommUserRepo;

class CheckRegistrationStatusMiddle extends Middleware
{
    public $user;

    public function __construct(CommUserRepo $user)
    {
        $this->user=$user;
    }

    public function handle($request, Closure $next)
    {
        //用户不存在需注册
        $criteria1=Criteria::create()
            ->where('login_name','=',$request['mobile']);
        $user_info=$this->user->first($criteria1);
        if(!empty($user_info)){
            $ret=[
                'status'=>'0020',
            ];
        }else{
            $ret=['status'=>'0000'];
        }
        return $ret;
    }
}