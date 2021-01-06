<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/11/28
 * Time: 10:13
 */

namespace Micro\User\Model;


use Illuminate\Database\Eloquent\Model;

class CommBankDb extends Model
{
    protected $table = "comm_bank_db";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}