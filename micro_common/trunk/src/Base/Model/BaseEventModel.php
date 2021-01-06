<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/1/7
 * Time: 15:29
 */

namespace Micro\Common\Base\Model;


use Illuminate\Database\Eloquent\Model;

class BaseEventModel extends Model
{

    protected $table = "base_event";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}