<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/5/29
 * Time: 18:06
 */

namespace Micro\Common\Common\Models;


use Illuminate\Database\Eloquent\Model;

class HyCommUserInfo extends Model
{
    protected $table = "comm_user_info";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}