<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/7
 * Time: 11:03
 */

namespace Micro\Common\Common\Models;


use Illuminate\Database\Eloquent\Model;

class InviteCode extends Model
{
    protected $table = "invite_code";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    //有效时间
    public function getEffectiveTimeAttribute($value)
    {
        if($value) return date('Y-m-d',strtotime($value));
        return '永久有效';
    }

    //使用时间
    public function getUseTimeAttribute($value){
        if($value) return date('Y-m-d',strtotime($value));
        return '';
    }

    public function setCreateTimeAttribute(){
        return date('Y-m-d H:i:s');
    }

    public function setUpdateTimeAttribute(){
        return date('Y-m-d H:i:s');
    }
}