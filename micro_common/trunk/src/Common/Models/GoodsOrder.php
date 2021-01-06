<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/9
 * Time: 17:50
 */

namespace Micro\Common\Common\Models;


use Illuminate\Database\Eloquent\Model;

class GoodsOrder extends Model
{
    protected $table = "goods_order";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}