<?php
namespace Micro\Common\Common\Models;

use Illuminate\Database\Eloquent\Model;

class BaseActivityOrder extends Model
{
    protected $table = "base_activity_order";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
    
}