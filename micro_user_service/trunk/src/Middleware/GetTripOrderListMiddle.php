<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/11/1
 * Time: 14:51
 */

namespace Micro\User\Middleware;


use Closure;
use Micro\Common\Contract\Middleware;

class GetTripOrderListMiddle extends Middleware
{
    public function handle($request, Closure $next)
    {
        $data = app('micro-client')
            ->micro('GetTripOrderListService')
            ->service('Micro\\OrderDispatch\\Service\\GetTripOrderListService')
            ->pass($request)
            ->run('getTripOrderListUser');
        return $data['data'];
    }
}