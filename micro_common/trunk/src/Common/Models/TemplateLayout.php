<?php

namespace Micro\Common\Common\Models;


use Illuminate\Database\Eloquent\Model;

class TemplateLayout extends Model
{
    protected $table = "template_layout";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

//    public function getJumpUrlAttribute($value)
//    {
//        if($value){
//            return R($value,false);
//        }
//    }
    public function getIconsAttribute($value)
    {
        if($value){
            return R($value,false);
        }
    }
}