<?php

namespace Micro\Common\Common\Models;

use Illuminate\Database\Eloquent\Model;


class UserBank extends Model
{
    protected $table = "user_bank";
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}