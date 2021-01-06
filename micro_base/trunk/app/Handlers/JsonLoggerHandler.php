<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/7/12
 * Time: 12:36
 */

namespace App\Handlers;


use Monolog\Formatter\JsonFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Util\Json;

class JsonLoggerHandler extends  StreamHandler
{

    public function __construct()
    {
        $level = Logger::DEBUG;
        $stream = storage_path('logs/log-' . date('Y-m-d') . '.log');
        parent::__construct($stream, $level);
        $this->setFormatter($this->getCustomFormatter());
    }


    public function handleBatch(array $records)
    {
        $request = app('request');
        $rand = rand(100000000,999999999);
        $log_append = [
            'log_time' => date('Y-m-d H:i:s'),
            'request_id' =>  $rand,
            'client_ip' =>$request->getClientIp(),
            'request_path' => $request->path()

        ];
        $ret = "";
        // 然后将内存中的日志追加到$log这个变量里
        foreach ($records as $record) {
            if (!$this->isHandling($record)) {
                continue;
            }
            $data['log_message'] = $record['message'];
            $log = $this->processRecord(array_merge($log_append,$data));
            $ret .=  $this->getFormatter()->format($log);
        }
        // 调用日志写入方法
        $this->write(['formatted' => $ret]);

    }

    protected function getCustomFormatter()
    {
        return new JsonFormatter();
//        return new LineFormatter(
//            "%level_name%|%message%\n",
//            true,
//            true
//        );
    }
}