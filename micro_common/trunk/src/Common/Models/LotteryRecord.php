<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/16
 * Time: 14:56
 */

namespace Micro\Common\Common\Models;


use Illuminate\Database\Eloquent\Model;

class LotteryRecord extends Model
{
    protected $table = "lottery_record";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}