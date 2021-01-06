<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/11/20
 * Time: 11:36
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class CashOrder extends Model
{
    protected $table = "cash_order";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}