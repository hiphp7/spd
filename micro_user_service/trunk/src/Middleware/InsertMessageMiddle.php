<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/31
 * Time: 13:59
 */

namespace Micro\User\Middleware;


use Closure;
use Illuminate\Support\Facades\Log;
use Micro\Common\Contract\Middleware;
use Micro\User\Repo\MessageRepo;

class InsertMessageMiddle extends Middleware
{
    public $repo;

    public function __construct(MessageRepo $repo)
    {
        $this->repo=$repo;
    }

    public function handle($request, Closure $next)
    {
        Log::info('插入消息');
        $param=[
            'id'=>ID(),
            'from_id'=>$request['user_id'],
            'to_id'=>$request['to_id'],
            'order_id'=>$request['order_id'],
            'content'=>$request['message'],
            'create_time'=>date('Y-m-d H:i:s'),
            'create_by'=>$request['user_id'],
            'update_time'=>date('Y-m-d H:i:s'),
            'update_by'=>$request['user_id'],
            'read_status'=>'0010',
        ];
        $this->repo->insert($param);
        return '0000';
//        return $next($request);
    }
}