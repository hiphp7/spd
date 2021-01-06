<?php

namespace Micro\Common\Common\Models;


use Illuminate\Database\Eloquent\Model;

class TemplateAdvert extends Model
{
    protected $table = "template_advert";
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

    //由于鹏云APP暂为升级,先以项目名称区分数据格式,升级后将统一数据格式
    public function getImgAttribute($value)
    {

//        if($value){
//            return R($value,false);
//        }
        if(env('PROJECT_NAME') != 'pengyun'){
            if($value){
                $ex = explode(',',$value);
                foreach($ex as $key => $val){
                    $ex[$key] = R($val,false);
                }
                return $ex;
            }
        }else if($value){
            return R($value,false);
        }
        return $value;
    }
}