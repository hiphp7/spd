<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/11
 * Time: 17:45
 */
namespace amap\sdk\TsApi;

use amap\sdk\core\traits\ClientTrait;

class TsAPI
{
    use ClientTrait;

    /**
     * 创建服务
     * @return ServiceAdd
     */
    public static function serviceAdd(){
        return (new ServiceAdd());
    }

    /**
     * 服务查询
     * @return ServiceList
     */
    public static function serviceList(){
        return (new ServiceList());
    }

    /**
     * 终端创建
     * @return TerminalAdd
     */
    public static function terminalAdd()
    {
        return (new TerminalAdd());
    }

    /**
     * 创建轨迹
     * @return TerminalAdd
     */
    public static function traceAdd()
    {
        return (new TerminalAdd());
    }

    /**
     * 轨迹点上传（单点、批量）
     * @return PointUpload
     */
    public static function pointUpload()
    {
        return (new PointUpload());
    }

    /**
     * @desc 查询轨迹信息（里程、时间等）
     * @return TerminalTrsearch
     */
    public static function terminalTrsearch()
    {
        return (new TerminalTrsearch());
    }
}