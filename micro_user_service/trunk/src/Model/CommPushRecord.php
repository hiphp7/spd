<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/30
 * Time: 10:34
 */

namespace Micro\User\Model;


use Illuminate\Database\Eloquent\Model;

class CommPushRecord extends Model
{
    protected $table = "comm_push_record";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    public function getCreateTimeAttribute($value)
    {
        return date('Y-m-d',strtotime($value));
//        return Access::Service('CommonService')
//            ->with('time',$value)
//            ->run('transTime');
    }
}