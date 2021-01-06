<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/3
 * Time: 16:22
 */

namespace Micro\Sms\Repository;


use Micro\Common\Contract\Repository;
use Micro\Sms\Model\SmsCommPushRecordModel;

class SmsCommPushRecordRepo extends Repository
{
    public function __construct(SmsCommPushRecordModel $model)
    {
        $this->model = $model;
    }
}