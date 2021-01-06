<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/30
 * Time: 14:30
 */

namespace Micro\User\Repo;


use Micro\Common\Contract\Repository;
use Micro\User\Model\MessageModel;

class MessageRepo extends Repository
{
    public $model;

    public function __construct(MessageModel $model)
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
    public function getListByOrderIdTime($page=1,$pagesize,$order_id,$create_time)
    {
        $ret = optional($this->model
            ->select('id','from_id','to_id','content','create_time','order_id')
            ->where('order_id','=',$order_id)
            ->where('create_time','<',$create_time)
            ->orderBy('create_time','desc')
            ->paginate($pagesize)
        )->toArray();
        return $ret['data'];
    }

    public function getListByOrderId($page=1,$pagesize,$order_id)
    {
        $ret = optional($this->model
            ->select('id','from_id','to_id','content','create_time','order_id')
            ->where('order_id','=',$order_id)
            ->orderBy('create_time','desc')
            ->paginate($pagesize)
        )->toArray();
        return $ret['data'];
    }

    public function UpdateByOrderID($order_id,$param){
        return $this->model->where('order_id',$order_id)->where('read_status','0010')->update($param);
    }
}