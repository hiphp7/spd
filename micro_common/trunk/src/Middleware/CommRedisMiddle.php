<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/16
 * Time: 18:28
 */
namespace Micro\Common\Middleware;

use Closure;
use Micro\Common\Contract\Middleware;

class CommRedisMiddle extends Middleware
{
    public $connection;
    public $redis;
    public $db;
    public function __construct($connection = 'default')
    {
        $this->connection = $connection;
        $this->redis = app('redis')->connection($this->connection);
    }

    public function handle($request, Closure $next)
    {
        $this->db = $request['db'];
        $ret = $this->hmget($request['key'],$request['dictionary']);
        dd($ret);
    }

    public function hmget($key,$dictionary){
        $this->redis->select(1);
        return $this->redis->hmget(env('QUEUE_NAME').$key,$dictionary);
    }
}