<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/8/5
 * Time: 13:57
 */

namespace Micro\Common\Base\Model;


use Illuminate\Database\Eloquent\Model;

class BaseBroadcastChannelModel extends Model
{
    protected $table = "base_broadcast_channel";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}