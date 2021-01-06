<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/8/5
 * Time: 13:58
 */

namespace Micro\Common\Base\Repository;

use Micro\Common\Base\Model\BaseBroadcastChannelModel;
use Micro\Common\Contract\Repository;
use Micro\Common\Criteria\Criteria;

class BaseBroadcastChannelRepo extends Repository
{
    public function __construct(BaseBroadcastChannelModel $model)
    {
        $this->model = $model;
    }

    public function getChannels($service)
    {
        $criteria= Criteria::create()
            ->where('broadcast_service',$service)
            ->where('status',1)
            ->orderBy('order','asc');

        return $this->cacheGet(
            $service,
            config('cache.repo.long'),
            $criteria,
            ['*']
        );
    }
}