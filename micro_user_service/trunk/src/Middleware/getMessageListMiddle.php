<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/30
 * Time: 10:33
 */

namespace Micro\User\Middleware;


use Closure;
use Illuminate\Support\Facades\Log;
use Micro\Common\Contract\Middleware;
use Micro\User\Repo\CommPushRecordRepo;

class getMessageListMiddle extends Middleware
{
    public $repo;

    public function __construct(CommPushRecordRepo $repo)
    {
        $this->repo=$repo;
    }

    public function handle($request, Closure $next)
    {
        Log::info('信息列表:|' .$request['user_id']);
        $ret = $this->repo->getListByProccessId(
            $request['user_id'],
            $request['page'],
            $request['pageSize'],
            $request['type'],
            $request['user_info']['commuser']['user_tariff_code']
        );
        foreach ($ret as $key => $val){
            $ret[$key]['img'] = config('const_sms.'.$val['msg_type'].'.img');
        }
        return $ret;
    }
}