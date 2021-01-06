<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/11/28
 * Time: 10:31
 */

namespace Micro\User\Model;


use Illuminate\Database\Eloquent\Model;

class SysCity extends Model
{
    protected $table = "sys_city";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
}