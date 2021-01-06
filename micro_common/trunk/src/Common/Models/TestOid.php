<?php
namespace Micro\Common\Common\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * 订单处理表
 */
class TestOid extends Model {

    protected $table = "test_oid";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

}
