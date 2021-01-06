<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/31
 * Time: 17:15
 */

namespace Micro\Common\Common\Models;

use Illuminate\Database\Eloquent\Model;

class PendingOrder extends Model {

    protected $table = "pending_order";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';


}