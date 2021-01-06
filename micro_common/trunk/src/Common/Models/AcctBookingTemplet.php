<?php
/**
 * User: satsun
 * Date: 2018/2/24
 * Time: 14:14
 */
namespace Micro\Common\Common\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * 财务模板表
 */
class AcctBookingTemplet extends Model {



    protected $table = "acct_booking_templet";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'created_time';
    const UPDATED_AT = 'updated_time';

}
