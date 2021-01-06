<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/29
 * Time: 10:16
 */
namespace Micro\Sms\Repository;
use Micro\Common\Contract\Repository;
use Micro\Sms\Model\CommSmsModel;

class CommSmsRepo extends Repository
{
    public function __construct(CommSmsModel $model)
    {
        $this->model = $model;
    }

    //获取验证码
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