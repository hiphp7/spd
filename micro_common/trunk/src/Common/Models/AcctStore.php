<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/3/14
 * Time: 13:44
 */

namespace Micro\Common\Common\Models;


use Illuminate\Database\Eloquent\Model;

class AcctStore extends Model
{

    protected $table = "acct_store";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    public function getprop2Attribute($value)
    {
        if($value){
            R($value);
        }
        return $value;
    }
}