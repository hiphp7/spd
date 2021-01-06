<?php

namespace Micro\Common\Common\Models;


use Illuminate\Database\Eloquent\Model;

class BalanceRefundOrder extends Model
{
    protected $table = "balance_refund_order";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}