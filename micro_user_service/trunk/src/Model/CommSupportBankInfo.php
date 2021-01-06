<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/11/28
 * Time: 10:18
 */

namespace Micro\User\Model;


use Illuminate\Database\Eloquent\Model;

class CommSupportBankInfo extends Model
{
    protected $table = "comm_support_bank_info";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    public function getImgUrlAttribute($value){

        return R($value);

    }
}