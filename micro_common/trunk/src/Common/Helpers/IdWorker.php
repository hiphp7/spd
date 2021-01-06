<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/23
 * Time: 13:40
 */

namespace Micro\Common\Common\Helpers;

class IdWorker
{

    //机器标识占的位数
    const pidBits = 10;
    //数据中心标识占的位数
    const serverBits = 4;
    //毫秒内自增数点的位数
    const sequenceBits = 8;
    //唯一进程ID
    protected $pid = 0;
    //物理机服务器ID
    protected $serverId;

    static $lastTimestamp = -1;
    static $sequence = 0;
    //内存共享信号
    protected $signal;


    function __construct()
    {
        $maxServerId = -1 ^ (-1 << self::serverBits);
        $server_id =config('app.server_id');
        if ($server_id<0 || $server_id >$maxServerId) {
            throw new Exception("服务器ID超出范围( 1-16 ),请检查配置文件app.server_id ");
        }
        $pid =rand(1,1023);
        $this->serverId = $server_id;
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
            $this->pid = $pid;
        }else{
            try{
                $this->pid = $this->getAcquireId($pid);
            }catch (\Exception $e){
                $this->pid = $pid;
            }
        }


    }
    //通过信号量锁定唯一ID
    public function getAcquireId($id)
    {
        $this->signal = sem_get($id);
        if(sem_acquire($this->signal)){
            return $id;
        }else{
            $this->getSignal(rand(1,1023));
        }
    }

    //生成一个ID
    public function getId()
    {
        $timestamp = $this->timeGen();
        $twepoch = $this->twepochGen();
        $lastTimestamp = self::$lastTimestamp;
        //判断时钟是否正常
        if ($timestamp < $lastTimestamp) {
            throw new Exception("时钟不正常，不可产生id", ($lastTimestamp - $timestamp));
        }
        //生成唯一序列
        if ($lastTimestamp == $timestamp) {
            $sequenceMask = -1 ^ (-1 << self::sequenceBits);
            self::$sequence = (self::$sequence + 1) & $sequenceMask;
            if (self::$sequence == 0) {
                $timestamp = $this->tilNextMillis($lastTimestamp);
            }
        } else {
            self::$sequence = 0;
        }
        self::$lastTimestamp = $timestamp;
        //时间毫秒/数据中心ID/机器ID,要左移的位数
        $timestampLeftShift = self::sequenceBits + self::pidBits + self::serverBits;
        $serverIdShift = self::sequenceBits + self::pidBits;
        $pidShift = self::sequenceBits;
        //组合4段数据返回: 时间戳.数据标识.工作机器.序列
        $nextId = (($timestamp - $twepoch) << $timestampLeftShift)
            |($this->serverId << $serverIdShift)
            |($this->pid << $pidShift)
            | self::$sequence
        ;
        if(PHP_INT_SIZE == 4){
            $id = (String)($timestamp - $twepoch) . rand(100000,999999) ;
        }else{
            $id = (String)$nextId;
        }
        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
            try{
                sem_release($this->signal);
                sem_remove($this->signal);
            }
            catch (\Exception $e){}
        }

        return $id;
    }

    //取当前时间毫秒
    protected function timeGen()
    {
        $timestramp = (float)sprintf("%.0f", microtime(true) * 1000);
        return  $timestramp;
    }

    //取下一毫秒
    protected function tilNextMillis($lastTimestamp)
    {
        $timestamp = $this->timeGen();
        while ($timestamp <= $lastTimestamp) {
            $timestamp = $this->timeGen();
        }
        return $timestamp;
    }

    //开始时间,固定一个小于当前时间的毫秒数即可
    protected function twepochGen()
    {
        $timer = "2010-01-01 00:00:00";
        $ret = (float)sprintf("%.0f", strtotime($timer)*1000);
        return $ret ;
    }

}