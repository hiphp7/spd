<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/11/7
 * Time: 14:24
 */

namespace Micro\User\Repo;


use Micro\Common\Contract\Repository;
use Micro\User\Model\CommFeedback;

class CommFeedbackRepo extends Repository
{
    public function __construct( CommFeedback $model)
    {
        $this->model = $model;
    }
}