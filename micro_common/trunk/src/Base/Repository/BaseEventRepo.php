<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/1/7
 * Time: 15:30
 */

namespace Micro\Common\Base\Repository;


use Micro\Common\Contract\Repository;
use Micro\Common\Criteria\Criteria;
use Micro\Common\Base\Model\BaseEventModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class BaseEventRepo extends Repository
{

    public function __construct(BaseEventModel $model)
    {
        $this->model = $model;
    }


    public function getBeforeEvent($event)
    {
        $criteria = Criteria::create()
            ->where('event',$event)
            ->where('status',1)
            ->where('type','10')
            ->orderBy('order','asc');

        return $this->cacheGet(
            $event,
            config('cache.repo.long'),
            $criteria,
            ['*']
        );
    }


    public function getAfterEvent($event)
    {

        $criteria = Criteria::create()
            ->where('event',$event)
            ->where('status',1)
            ->where('type','20')
            ->orderBy('order','asc');




        return $this->cacheGet(
            $event,
            config('cache.repo.long'),
            $criteria,
            ['*']
        );
    }


}