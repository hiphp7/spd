<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/11/28
 * Time: 10:56
 */

namespace Micro\User\Repo;


use Micro\Common\Contract\Repository;
use Micro\User\Model\SysCity;

class SysCityRepo extends Repository
{
    public function __construct(SysCity $model)
    {
        $this->model = $model;
    }

    /**
     * 省列表
     */
    public function getProvinceList()
    {
        $ret = optional($this->model
            ->select('id','name','parentid')
            ->where('parentid',0)
            ->orderBy('sort','asc')
            ->get())
            ->toArray();
        return $ret;
    }

    /**
     * 根据省ID,获取对应市列表
     */
    public function getCityListByProvinceId($provinceId)
    {
        $ret = optional($this->model
            ->select('id','name','parentid')
            ->where('parentid',$provinceId)
            ->where('status',1)
            ->orderBy('sort','asc')
            ->get())
            ->toArray();
        return $ret;
    }
}