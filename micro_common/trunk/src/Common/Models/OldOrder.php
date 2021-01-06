<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/3
 * Time: 20:30
 */

namespace Micro\Common\Common\Models;
use Illuminate\Database\Eloquent\Model;

class OldOrder extends Model
{
    protected $table = "old_order";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}