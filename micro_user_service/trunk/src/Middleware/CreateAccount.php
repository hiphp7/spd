<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/30
 * Time: 8:58
 */

namespace Micro\User\Middleware;


use Closure;
use Illuminate\Support\Facades\Log;
use Micro\Common\Contract\Middleware;

class CreateAccount extends Middleware
{
    public function handle($request, Closure $next)
    {
        Log::info('创建账户 ->' .$request['user_id']);
        app('micro-client')
            ->service('Micro\Finance\Service\CreateAccountService')
            ->micro('aaa')
            ->with('acct_id',1)
            ->with('user_id',$request['user_id'])
            ->run('createAccount');

        return $next($request);
    }
}