<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/9/18
 * Time: 10:28
 */

namespace Micro\Common\Model;


use Illuminate\Database\Eloquent\Model;

class CommSms extends Model
{
    protected $table = "comm_sms";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}