<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/11/20
 * Time: 11:29
 */

namespace App\Model;


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