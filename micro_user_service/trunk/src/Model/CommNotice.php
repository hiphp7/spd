<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/11/7
 * Time: 14:34
 */

namespace Micro\User\Model;


use Illuminate\Database\Eloquent\Model;

class CommNotice extends Model
{
    protected $table = "comm_notice";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}