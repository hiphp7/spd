<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/31
 * Time: 13:58
 */

namespace Micro\User\Repo;


use Micro\Common\Contract\Repository;
use Micro\User\Model\BaseinfoOrderModel;

class BaseinfoOrderRepo extends Repository
{
    public function __construct(BaseinfoOrderModel $model)
    {
        $this->model = $model;
    }

    /**
     * 根据订单明细更新
     * @param $order_id string 订单明细ID
     * @param $attributes
     * @return mixed
     */
    public function updateByOrderId($order_id,$attributes){
        return $this->model->where('order_id',$order_id)->update($attributes);
    }

    /**
     * @param $user_id,$pageNum,$pageSize,$tariff_code
     * @param user_id string 用户ID
     * @param $pageNum number 页码
     * @param $pageSize number 条数
     * @param $tariff_code 用户等级
     */
    public function getListByDriverIdType($user_id,$page=1,$pagesize,$type)
    {
        $ret = optional($this->model
            ->select('id','dep_are','dep_time','dest_area','dest_time','status','order_type')
            ->where('driver_id',$user_id)
            ->where('order_type',$type)
            ->orderBy('create_time','desc')
            ->paginate($pagesize)
        )->toArray();
        return $ret['data'];
    }

    public function getListByDriverId($user_id,$page=1,$pagesize)
    {
        $ret = optional($this->model
            ->select('id','dep_are','dep_time','dest_area','dest_time','status','order_type')
            ->where('driver_id',$user_id)
            ->orderBy('create_time','desc')
            ->paginate($pagesize)
        )->toArray();
        return $ret['data'];
    }
}