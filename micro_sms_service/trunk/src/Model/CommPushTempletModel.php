<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/29
 * Time: 10:15
 */

namespace Micro\Sms\Model;


use Illuminate\Database\Eloquent\Model;

/**
 * @desc 消息模版
 * Class CommPushTempletModel
 * @package Micro\Sms\Model
 */
class CommPushTempletModel extends Model
{
    protected $table = "comm_push_templet";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
}