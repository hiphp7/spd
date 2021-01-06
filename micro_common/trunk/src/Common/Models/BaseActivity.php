<?php
namespace Micro\Common\Common\Models;

use Illuminate\Database\Eloquent\Model;

class BaseActivity extends Model
{
    protected $table = "base_activity";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    public function getImageAttribute($value)
    {
        if($value){
            return R($value,false);
        }
    }
}