<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/31
 * Time: 13:57
 */

namespace Micro\User\Model;


use Illuminate\Database\Eloquent\Model;

class BaseinfoOrderModel extends Model
{
    protected $table = "baseinfo_order";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
}