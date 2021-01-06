<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/29
 * Time: 10:17
 */

namespace Micro\Sms\Repository;
use Micro\Common\Contract\Repository;
use Micro\Sms\Model\CommPushTempletModel;


class CommPushTempletRepo extends Repository
{
    public function __construct(CommPushTempletModel $model)
    {
        $this->model = $model;
    }

    //查询短信模版
    public function getSmsTemplet($code)
    {
        return optional($this->model
            ->select('title','templet_id','content')
            ->where('business_code',$code)
            ->first())
            ->toArray();
    }
}