<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/3/6
 * Time: 18:33
 */

namespace Micro\Common\Common\Base;


use Micro\Common\Common\Contracts\Repository;
use Micro\Common\Base\Base;
class Repo extends Repository
{
    public static function model($model = null)
    {
        return Base::repo($model);
    }
}