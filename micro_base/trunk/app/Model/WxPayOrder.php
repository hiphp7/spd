<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/11/20
 * Time: 11:35
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class WxPayOrder extends Model
{
    protected $table = "wx_pay_order";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}