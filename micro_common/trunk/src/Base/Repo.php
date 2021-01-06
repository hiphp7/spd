<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/3/6
 * Time: 18:33
 */

namespace Micro\Common\Base;

use Micro\Common\Contract\Repository;

class Repo extends Repository
{
    public static function model($model = null)
    {
        return Base::repo($model);
    }
}