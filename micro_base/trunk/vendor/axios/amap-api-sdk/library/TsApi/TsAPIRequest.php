<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/11
 * Time: 17:45
 */

namespace amap\sdk\TsApi;

use amap\sdk\AMapOption;
use amap\sdk\core\AMapRequest;

class TsAPIRequest extends AMapRequest
{
    public function __construct(string $action)
    {
        parent::__construct($action);
        $this->request_base_url = AMapOption::TS_API_REQUEST_BASE_URL;
    }
}