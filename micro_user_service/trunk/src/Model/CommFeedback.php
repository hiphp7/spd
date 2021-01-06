<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/11/7
 * Time: 14:23
 */

namespace Micro\User\Model;


use Illuminate\Database\Eloquent\Model;

class CommFeedback extends Model
{
    protected $table = "comm_feedback";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}