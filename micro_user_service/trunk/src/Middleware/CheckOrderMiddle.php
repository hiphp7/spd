<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/31
 * Time: 13:56
 */

namespace Micro\User\Middleware;


use Closure;
use Illuminate\Support\Facades\Log;
use Micro\Common\Contract\Middleware;
use Micro\Common\Criteria\Criteria;
use Micro\User\Repo\BaseinfoOrderRepo;

class CheckOrderMiddle extends Middleware
{
    public $repo;

    public function __construct(BaseinfoOrderRepo $repo)
    {
        $this->repo=$repo;
    }

    public function handle($request, Closure $next)
    {
        Log::info('检测订单');
        $criteria=Criteria::create()
            ->where('order_id',$request['order_id']);
        $order_info=$this->repo->first($criteria);
        if(empty($order_info)){
            Err('订单不存在');
        }
        return $next($request);
    }
}