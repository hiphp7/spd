<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/25
 * Time: 11:12
 */

namespace Micro\Common\Common\Models;


use Illuminate\Database\Eloquent\Model;

class ProfitOrder extends Model
{
    protected $table = "profit_order";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    protected $fillable = [
        'id',
        'user_id',
        'handle_type',
        'title',
        'account_type',
        'relation_id',
        'type',
        'amount',
        'status',
        'settle_time',
        'create_time',
        'create_by',
        'update_time',
        'update_by',
        'cycle',
        'remark',
    ];
}