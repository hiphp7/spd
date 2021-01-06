<?php
namespace Micro\Common\Common\Models;

use Illuminate\Database\Eloquent\Model;

class MallGoodsType extends Model
{
    protected $table = "mall_goods_type";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

}