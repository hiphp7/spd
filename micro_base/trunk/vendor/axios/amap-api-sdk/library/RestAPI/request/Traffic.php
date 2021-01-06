<?php
/**
 * @author: axios
 *
 * @email: axiosleo@foxmail.com
 * @blog:  http://hanxv.cn
 * @datetime: 2018/3/28 17:04
 */

namespace amap\sdk\RestAPI\request;

use amap\sdk\core\exception\RestAPIException;

/**
 * Class Traffic
 * @package amap\sdk\RestAPI\request
 * @method TrafficRequest rectangle()
 * @method TrafficRequest circle()
 * @method TrafficRequest road()
 */
class Traffic
{
    protected $actionArray = [
        'rectangle' => 'traffic/status/rectangle',//矩形区域交通态势
        'circle' => 'v3/traffic/status/circle',//圆形区域交通态势
        'road' => 'v3/traffic/status/road'//制定线路交通态势
    ];

    /**
     * @param $name
     * @param $arguments
     * @return GeoRequest
     * @throws RestAPIException
     */
    public function __call($name, $arguments)
    {
        if (!isset($this->actionArray[$name])) {
            throw new RestAPIException("action not exist");
        }
        $Class = new GeoRequest($this->actionArray[$name]);
        return $Class;
    }
}