<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/30
 * Time: 14:29
 */

namespace Micro\User\Repo;


use Micro\Common\Contract\Repository;
use Micro\User\Model\CommChatModel;

class CommChatRepo extends Repository
{
    public $model;

    public function __construct(CommChatModel $model)
    {
        $this->model=$model;
    }

    /**
     * @param $user_id,$pageNum,$pageSize,$tariff_code
     * @param user_id string 用户ID
     * @param $pageNum number 页码
     * @param $pageSize number 条数
     * @param $tariff_code 用户等级
     */
    public function getListByDriverId($driver_id,$page=1,$pagesize)
    {
        $ret = optional($this->model
            ->select('*')
            ->where('driver_id',$driver_id)
            ->orderBy('update_time','desc')
            ->paginate($pagesize)
        )->toArray();
        return $ret['data'];
    }

    /**
     * @param $user_id,$pageNum,$pageSize,$tariff_code
     * @param user_id string 用户ID
     * @param $pageNum number 页码
     * @param $pageSize number 条数
     * @param $tariff_code 用户等级
     */
    public function getListByPassengerId($user_id,$page=1,$pagesize)
    {
        $ret = optional($this->model
            ->select('*')
            ->where('user_id',$user_id)
            ->orderBy('update_time','desc')
            ->paginate($pagesize)
        )->toArray();
        return $ret['data'];
    }
}