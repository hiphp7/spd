<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/3/15
 * Time: 14:43
 */

namespace Micro\Common\Base;



use Micro\Common\Contract\Repository;

class BaseRepo extends Repository
{
    public $model;

    public function __construct()
    {
        $this->model = new BaseModel();
    }
}