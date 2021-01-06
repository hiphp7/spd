<?php
/**
 * User: satsun
 * Date: 2018/2/24
 * Time: 14:14
 */
namespace Micro\Common\Common\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * 物流信息表
 */
class ExpressInfo extends Model {



    protected $table = "express_info";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

}
