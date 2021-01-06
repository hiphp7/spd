<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/11/4
 * Time: 9:26
 */

namespace Micro\User\Model;


use Illuminate\Database\Eloquent\Model;

class BaseinfoEmergencyContactModel extends Model
{
    protected $table = "baseinfo_emergency_contact";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
}