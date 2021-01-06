<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/8/5
 * Time: 12:56
 */

namespace Micro\Common\Base\Repository;


use Micro\Common\Base\Model\BaseBroadcastModel;
use Micro\Common\Contract\Repository;
use Micro\Common\Criteria\Criteria;

class BaseBroadcastRepo extends Repository
{
    public function __construct(BaseBroadcastModel $model)
    {
        $this->model = $model;
    }

    public function getBroadcast($broadcast)
    {
        $criteria = Criteria::create()
            ->where('broadcast',$broadcast)
            ->where('status',1)
            ->orderBy('order','asc');

        return $this->cacheGet(
            $broadcast,
            config('cache.repo.long'),
            $criteria,
            ['*']
        );
    }
}