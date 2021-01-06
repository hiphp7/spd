<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/11
 * Time: 18:05
 */

namespace amap\sdk\TsApi\request;


use amap\sdk\core\traits\RequestTrait;
use amap\sdk\TsApi\TsAPIRequest;

/**
 * Class FalconRequest
 * @package amap\sdk\TsAPI\request
 * @method $this setSid($sid)
 */
class FalconRequest extends TsAPIRequest{
    use RequestTrait;

    public $actionArray = [
        'service_add'   => 'v1/track/service/add', //创建服务
        'service_list'  => 'v1/track/service/list',//查询服务
        'terminal_add'  => 'v1/track/terminal/add',//创建终端
        'trace_add'     => 'v1/track/trace/add',//创建轨迹
        'point_upload'  => 'v1/track/point/upload',//轨迹点上传（单点、批量）
        'terminal_trsearch' => 'v1/track/terminal/trsearch'//查询轨迹信息（里程、时间等）
    ];

    public function __construct(string $action)
    {
        parent::__construct($this->actionArray[$action]);
    }
}