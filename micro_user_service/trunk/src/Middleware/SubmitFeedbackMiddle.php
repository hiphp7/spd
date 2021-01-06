<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/11/7
 * Time: 14:25
 */

namespace Micro\User\Middleware;


use Closure;
use Illuminate\Support\Facades\Log;
use Micro\Common\Contract\Middleware;
use Micro\User\Repo\CommFeedbackRepo;

class SubmitFeedbackMiddle extends Middleware
{
    public $repo;

    public function __construct(CommFeedbackRepo $repo)
    {
        $this->repo=$repo;
    }

    public function handle($request, Closure $next)
    {
        Log::info('提交反馈');
        $param=[
            'id'=>ID(),
            'user_id'=>$request['user_id'],
            'basic_info'=>$request['basic_info'],
            'content'=>$request['content'],
            'user_type'=>'0010',
            'status'=>'10',
            'create_time'=>date('Y-m-d H:i:s'),
            'create_by'=>$request['user_id'],
            'update_time'=>date('Y-m-d H:i:s'),
            'update_by'=>$request['user_id'],
        ];
        if(!empty($request['img'])){
//            $param['img']=implode(',',$request['img']);
            $param['img']=$request['img'];
        }
        $this->repo->insert($param);

        return '0000';
    }
}