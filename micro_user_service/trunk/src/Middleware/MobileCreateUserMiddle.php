<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/29
 * Time: 16:25
 */

namespace Micro\User\Middleware;


use Closure;
use Illuminate\Support\Facades\Log;
use Micro\Common\Contract\Middleware;
use Micro\User\Repo\CommUserRepo;

class MobileCreateUserMiddle extends Middleware
{
    public $repo;

    public function __construct(CommUserRepo $repo)
    {
        $this->repo=$repo;
    }

    public function handle($request, Closure $next)
    {
        Log::info('创建用户->'.$request['mobile']);
        $id=ID();
        if(!isset($request['userName'])||(isset($request['userName'])&&!$request['userName'])){
            $request['userName'] = '昵称'.substr($request['mobile'],-4);
        }
        $data=[
            'id'               => $id,
            'user_id'          => $id,
            'user_name'        => $request['userName'],
            'user_type'        => 10,
            'login_name'       => $request['mobile'],
            'agent_id'         => config('const_user.FORMAL_AGENT.code'),
            'user_tariff_code' => 'P1101',
            'register_type'    => $request['register_type'],
            'level_name'       => config('const_user.ORDINARY_USER.code'),
            'status'           => config('const_user.SIGN_UP.code'),
            'pass_word'        => '',
            'last_login_time'  => date('Y-m-d H:i:s'),
            'create_time'      => date('Y-m-d H:i:s'),
            'create_by'        => 'system',
            'update_time'      => date('Y-m-d H:i:s'),
            'update_by'        => 'system',
        ];
        // 用户默认头像
        $data['headimgurl'] = R('headImg.png');
        //添加数据库
        $this->repo->insert($data);

        $request['headimgurl'] = $data['headimgurl'];
        $request['user_id'] = $id;
        $request['id'] = $id;
        $request['user_name'] = $data['user_name'];
        $request['user_data']=$data;
        return $next($request);
    }
}