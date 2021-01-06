<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/29
 * Time: 16:27
 */

namespace Micro\User\Middleware;


use Closure;
use Illuminate\Support\Facades\Log;
use Micro\Common\Contract\Middleware;
use Micro\User\Repo\CommUserRepo;
use Micro\User\Repo\TeamRelationRepo;

class CreateTeamMiddle extends Middleware
{
    public $repo;
    public $user;

    public function __construct(TeamRelationRepo $repo,CommUserRepo $user)
    {
        $this->repo = $repo;
        $this->user = $user;
    }

    public function handle($request, Closure $next)
    {
        Log::info('团推关系创建'.$request['recommendId']);

        $count = config('const_user.USER_MODEL_COUNT');

        $data = array(
            'id'               => ID(),
            'user_id'          => $request['user_id'],
            'user_name'        => $request['user_name'],
            'project_code'     => $request['project_code'],
            'model_count'      => $count,
            'parent1'          => $request['recommendId'],
            'status'           => 1,
            'create_by'        => 'system',
            'create_time'      => date('Y-m-d H:i:s'),
            'update_by'        => 'system',
            'update_time'      => date('Y-m-d H:i:s'),
        );

        $teamInfo = $this->repo->getRelation($request['recommendId']);
        if(!$teamInfo) Err('推荐用户获取失败');
        for($i = 1;$i < $count;$i++){
            $data['parent'.($i+1)] = $teamInfo['parent'.$i];
        }

        for($i = 5;$i <=10 ;$i++){
            $data['parent'.($i)] = $teamInfo['parent'.$i];
        }

        //保存数据库
        $this->repo->insert($data);
        $this->repo->updateRecommendRela($request['user_id'],$request['recommendId']);
        return $next($request);
    }
}