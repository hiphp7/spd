<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/9/18
 * Time: 10:29
 */

namespace Micro\Common\Repository;


use Micro\Common\Model\CommSms;
use Micro\Common\Contract\Repository;

class CommSmsRepo extends Repository
{
    public function __construct(CommSms $model)
    {
        $this->model = $model;
    }

    public function getMobileCaptcha($mobile)
    {
        $ret = optional($this->model
            ->select('id','mobile','captcha','create_time')
            ->where('mobile',$mobile)
            ->orderBy('create_time','desc')
            ->first())
            ->toArray();
        return $ret;
    }

    //获取当天数据
    public function getCountByMobile($mobile)
    {
        return $this->model
            ->select('*')
            ->where('mobile',$mobile)
            ->whereDate('create_time', date('Y-m-d'))
            ->count();
    }
}