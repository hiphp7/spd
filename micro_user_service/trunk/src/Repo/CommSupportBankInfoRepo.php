<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/11/28
 * Time: 10:18
 */

namespace Micro\User\Repo;


use Micro\Common\Contract\Repository;
use Micro\User\Model\CommSupportBankInfo;

class CommSupportBankInfoRepo extends Repository
{
    public function __construct(CommSupportBankInfo $model)
    {
        $this->model = $model;
    }

    public function getSupportBankList()
    {
        $ret = optional($this->model
            ->select('id', 'bank_id', 'bank_name', 'image_id')
            ->where('status', 1)
            ->get())
            ->toArray();
        return $ret;
    }
}