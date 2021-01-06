<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/1/7
 * Time: 13:54
 */

namespace Micro\Common\Base\Repository;


use Micro\Common\Contract\Repository;
use Micro\Common\Criteria\Criteria;
use Micro\Common\Base\Model\BaseServiceModel;

class BaseServiceRepo extends Repository
{
    
    public function __construct(BaseServiceModel $model)
    {
        $this->model = $model;
    }

    public function getService($service)
    {
        $criteria = Criteria::create()
            ->where('service',$service)
            ->where('status','1');

        return $this->cacheFirst(
            $service,
            config('cache.repo.long'),
            $criteria,
            ['*']
        );
    }

}