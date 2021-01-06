<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/4/11
 * Time: 17:53
 */

namespace Micro\Common\Base\Repository;


use Micro\Common\Contract\Repository;
use Micro\Common\Criteria\Criteria;
use Micro\Common\Base\Model\BaseJobModel;

class BaseJobRepo extends Repository
{
    public $model;

    public function __construct(BaseJobModel $model)
    {
        $this->model = $model;
    }

    public function getJob($job)
    {


        $criteria= Criteria::create()
            ->where('job',$job)
            ->where('status',1)
            ->orderBy('order','asc');

        return $this->cacheGet(
            $job,
            config('cache.repo.long'),
            $criteria,
            ['*']
        );
    }
}