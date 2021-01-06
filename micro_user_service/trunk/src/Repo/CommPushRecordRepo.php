<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/30
 * Time: 10:34
 */

namespace Micro\User\Repo;


use Micro\Common\Contract\Repository;
use Micro\User\Model\CommPushRecord;

class CommPushRecordRepo extends Repository
{
    public function __construct(CommPushRecord $model)
    {
        $this->model = $model;
    }

    /**
     * @param $user_id,$pageNum,$pageSize,$tariff_code
     * @param user_id string 用户ID
     * @param $pageNum number 页码
     * @param $pageSize number 条数
     * @param $tariff_code 用户等级
     */
    public function getListByProccessId($user_id,$page=1,$pagesize,$type,$user_tariff_code)
    {
        $ret = optional($this->model
            ->select('id','process_id_from','process_id_to','title','url','status','create_time','content','msg_type')
            ->where('status',1)
            ->whereIn('process_id_to',[$user_id,'all',$user_tariff_code,$type])
            ->orderBy('create_time','desc')
            ->paginate($pagesize)
        )->toArray();
        return $ret['data'];
    }

    /**
     * @查询消息内容
     * @param id string 消息id
     */
    public function getContentById($request)
    {
        $ret = optional($this->model
            ->select('title', 'content', 'status', 'create_time')
            ->where('id', $request['id'])
            ->first())
            ->toArray();
        return $ret;
    }
}