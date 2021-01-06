<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/4/16
 * Time: 13:46
 */

namespace Micro\Common\Base\Repository;


use Micro\Common\Contract\Repository;
use Micro\Common\Base\Model\BaseApiManageModel;

class BaseApiManageRepo extends Repository
{

    public $model;

    public function __construct(BaseApiManageModel $model)
    {
       $this->model = $model;
    }

}