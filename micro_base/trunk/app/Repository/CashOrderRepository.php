<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/11/20
 * Time: 11:38
 */

namespace App\Repository;


use App\Model\CashOrder;
use Micro\Common\Contract\Repository;

class CashOrderRepository extends Repository
{
    public $model;

    public function __construct(CashOrder $model)
    {
        $this->model = $model;
    }

    public function update($id, $params)
    {
        return $this->model->where('id', $id)->update($params);
    }
}