<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/29
 * Time: 10:14
 */
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/28
 * Time: 17:36
 */
namespace Micro\Sms\Model;
use Illuminate\Database\Eloquent\Model;
//短信
class CommSmsModel extends Model
{
    protected $table = "comm_sms";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
}