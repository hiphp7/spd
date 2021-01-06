<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/7/2
 * Time: 14:50
 */
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;


// implements AuthenticatableContract, AuthorizableContract

class CommUserInfo extends Model
{
    //    use Authenticatable, Authorizable;


    protected $table = "comm_user_info";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}