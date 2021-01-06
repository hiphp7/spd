<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/1/14
 * Time: 09:44
 */

namespace Micro\Common\Base\Model;


use Illuminate\Database\Eloquent\Model;

class UserCodeMasterModel extends Model
{
    protected $table = "user_code_master";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}