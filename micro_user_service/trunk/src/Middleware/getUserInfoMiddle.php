<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/30
 * Time: 10:38
 */

namespace Micro\User\Middleware;


use Closure;
use Illuminate\Support\Facades\Log;
use Micro\Common\Contract\Middleware;
use Micro\Common\Criteria\Criteria;
use Micro\User\Repo\CommUserRepo;
use Unirest\Exception;

class getUserInfoMiddle extends Middleware
{
    public $commUserRepo;

    public function __construct(CommUserRepo $commUserRepo)
    {
        $this->commUserRepo=$commUserRepo;
    }

    public function handle($request, Closure $next)
    {
        Log::info('检测用户是否存在---'.$request['user_id']);
//        try{
//            $redis = app('redis')->connection('default');
////            $redis->select(3);
//            $user_info = $redis->hgetall('user-info:user_id-'.$request['user_id']);
////            $driver_info=$driver_info[0];
//            if(empty($user_info)){
//                throw new Exception('1111');
//            }else{
//                $user_info['commuser']=json_decode($user_info['commuser'],true);
//            }
//            Log::info('获取司机信息---redis');
//        }catch (Exception $e) {
            $criteria=Criteria::create()
                ->where('driver_id','=',$request['user_id']);
            $commuser=$this->commUserRepo->find($request['user_id']);
            $arr=[
                'commuser'=>json_encode($commuser),
            ];
//            $redis = app('redis')->connection('default');
////            $redis->select(3);
//            $redis->hmset('user-info:user_id-'.$request['user_id'], $arr);
            $user_info=$arr;
            Log::info('获取用户信息---sql');
            $user_info['commuser']=json_decode($user_info['commuser'],true);
//        }

        if(empty($user_info)){
            Err('用户不存在');
        }
        $request['user_info']=$user_info;
        return $next($request);
    }
}