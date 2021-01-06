<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/11/28
 * Time: 9:18
 */

namespace Micro\User\Middleware;


use Closure;
use Illuminate\Support\Facades\Log;
use Micro\Common\Contract\Middleware;
use Micro\User\Repo\CommUserRepo;

class UpdateBankcardMiddle extends Middleware
{
    public $repo;
    public function __construct(CommUserRepo $repo)
    {
        $this->repo = $repo;
    }
    public function handle($request, Closure $next)
    {
        $data = [
//            'user_name'            => $user_name,
            'account_name'         => $request['accountName'],
            'account_no'           => $request['accountNo'],
            'bank_reserved_mobile' => $request['bankLeaveMobile'],
            'regist_address'       => $request['provinceName'].$request['cityName'],
            'open_bank_name'       => $request['openBankName'],
            'bank_line_name'       => $request['bankLineName'],
            'bank_line_code'       => $request['bankLineCode'],
            'bank_code'            => $request['bankCode'],
//            'status'               => config('const_user.OFFICIALLY.code'),
        ];

//        $request['data'] = $this->repo->where('user_id',$request['user_id'])->update($data);
        $re = $this->repo->updateUser($request['user_id'],$data);
        Log::info('UpdateBankcardMiddle'.json_encode($re));
        $request['data'] = $re;
        return $next($request);
    }
}