<?php

namespace Micro\Common\Common\Models;


use Illuminate\Database\Eloquent\Model;

class BankCode extends Model
{
    protected $table = "bank_code";
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = false;
//    const CREATED_AT = 'create_time';
//    const UPDATED_AT = 'update_time';
}