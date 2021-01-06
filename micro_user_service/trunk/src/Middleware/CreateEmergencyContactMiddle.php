<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/11/4
 * Time: 10:12
 */

namespace Micro\User\Middleware;


use Closure;
use Illuminate\Support\Facades\Log;
use Micro\Common\Contract\Middleware;
use Micro\User\Repo\BaseinfoEmergencyContactRepo;

class CreateEmergencyContactMiddle extends Middleware
{
    public $repo;

    public function __construct(BaseinfoEmergencyContactRepo $repo)
    {
        $this->repo=$repo;
    }

    public function handle($request, Closure $next)
    {
        Log::info('添加紧急联系人');
        $param=[
            'id'=>ID(),
            'user_id'=>$request['user_id'],
            'emergency_contact'=>$request['emergency_contact'],
            'emergency_contact_phone'=>$request['emergency_contact_phone'],
            'auto_share'=>$request['auto_share'],
            'time_slot'=>$request['time_slot'],
            'distance'=>$request['distance'],
            'create_time'=>date('Y-m-d H:i:s'),
            'create_by'=>'system',
            'update_time'=>date('Y-m-d H:i:s'),
            'update_by'=>'system',
        ];
        $this->repo->insert($param);

        return '0000';
    }
}