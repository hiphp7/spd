<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/12/4
 * Time: 13:41
 */

namespace Micro\User\Model;


use Illuminate\Database\Eloquent\Model;

class CommCodeMaster extends Model
{
    protected $table = "comm_code_master";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}