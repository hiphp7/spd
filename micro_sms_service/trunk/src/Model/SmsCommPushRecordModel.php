<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/3
 * Time: 16:22
 */

namespace Micro\Sms\Model;
use Illuminate\Database\Eloquent\Model;

class SmsCommPushRecordModel extends Model
{
    protected $table = "comm_push_record";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}