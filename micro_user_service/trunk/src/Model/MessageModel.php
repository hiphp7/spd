<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/30
 * Time: 14:29
 */

namespace Micro\User\Model;


use Illuminate\Database\Eloquent\Model;

class MessageModel extends Model
{
    protected $table = "message";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}