<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/1/7
 * Time: 14:08
 */

namespace Micro\Common\Base\Repository;


use Micro\Common\Contract\Repository;
use Micro\Common\Criteria\Criteria;
use Micro\Common\Base\Model\BaseMiddlewareModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class BaseMiddlewareRepo extends Repository
{

    public function __construct(BaseMiddlewareModel $model)
    {
        $this->model  = $model;
    }

    public function getMiddleware($middle)
    {


        $criteria= Criteria::create()
            ->where('middle',$middle)
            ->where('status',1)
            ->orderBy('order','asc');

        return $this->cacheGet(
            $middle,
            config('cache.repo.long'),
            $criteria,
            ['*']
        );
    }

}