<?php

namespace Micro\Common\Common\Models;


use Illuminate\Database\Eloquent\Model;

class TemplateFunction extends Model
{
    protected $table = "template_function";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    public function getImgAttribute($value)
    {
        if($value){
            return R($value,false);
        }
    }

    public function getBackImgAttribute($value)
    {
        if($value){
            return R($value,false);
        }
    }
}