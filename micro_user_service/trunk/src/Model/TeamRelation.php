<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/29
 * Time: 16:28
 */

namespace Micro\User\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Laravel\Lumen\Auth\Authorizable;

class TeamRelation extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;
    protected $table = "team_relation";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';


    public function getCreateTimeAttribute()
{
    $value = $this->attributes['create_time'];

    return date('Y-m-d',strtotime($value));
}

    public function getLoginNameAttribute()
{
    $value = $this->attributes['login_name'];

    return substr($value, 0, 5).'****'.substr($value, 9);
}
}