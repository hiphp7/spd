<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/4/16
 * Time: 13:44
 */

namespace Micro\Common\Base\Model;


use Illuminate\Database\Eloquent\Model;

class BaseApiManageModel extends Model
{

    protected $table = "base_api_manage";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

}