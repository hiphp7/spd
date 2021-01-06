<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/28
 * Time: 9:19
 */

namespace Micro\Common\Common\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * @desc 用户虚拟商品表
 * Class UserVirtualGoods
 * @package Micro\Common\Common\Models
 */
class UserVirtualGoods extends Model
{
    protected $table = "user_virtual_goods";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    public function getEndDateAttribute($value){
        if($value){
            return date('Y-m-d',strtotime($value));
        }
        return $value;
    }
}