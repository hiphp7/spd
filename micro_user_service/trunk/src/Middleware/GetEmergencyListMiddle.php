<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/11/4
 * Time: 11:00
 */

namespace Micro\User\Middleware;


use Closure;
use Micro\Common\Contract\Middleware;
use Micro\User\Repo\BaseinfoEmergencyContactRepo;

class GetEmergencyListMiddle extends Middleware
{
    public $repo;

    public function __construct(BaseinfoEmergencyContactRepo $repo)
    {
        $this->repo=$repo;
    }

    public function handle($request, Closure $next)
    {

        $ret = $this->repo->getListByPassengerId(
            $request['user_id'],
            $request['page'],
            $request['pageSize']
        );
        return $ret;
    }
}