<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/11/4
 * Time: 9:25
 */

namespace Micro\User\Middleware;


use Closure;
use Illuminate\Support\Facades\Log;
use Micro\Common\Contract\Middleware;
use Micro\Common\Criteria\Criteria;
use Micro\User\Repo\BaseinfoEmergencyContactRepo;

class CheckEmergencyContactMiddle extends Middleware
{
    public $repo;

    public function __construct(BaseinfoEmergencyContactRepo $repo)
    {
        $this->repo=$repo;
    }

    public function handle($request, Closure $next)
    {
        Log::info('检测紧急联系人数量');
        $criteria=Criteria::create()
            ->where('user_id',$request['user_id']);
        $count=$this->repo->count($criteria);
        if($count>=5){
            Err('紧急联系人最多可添加五人');
        }

        return $next($request);
    }
}