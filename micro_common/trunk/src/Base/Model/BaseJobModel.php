<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/4/11
 * Time: 17:52
 */

namespace Micro\Common\Base\Model;


use Illuminate\Database\Eloquent\Model;

class BaseJobModel extends Model
{

    protected $table = "base_job";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}