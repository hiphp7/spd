<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/11
 * Time: 15:43
 */

namespace Micro\Common\Common\Models;


use Illuminate\Database\Eloquent\Model;

class MallFlashOrder extends Model
{
    protected $table = "mall_flash_order";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}