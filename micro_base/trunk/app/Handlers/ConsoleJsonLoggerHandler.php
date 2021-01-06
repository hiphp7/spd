<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/7/12
 * Time: 12:57
 */

namespace App\Handlers;


use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class ConsoleJsonLoggerHandler extends StreamHandler
{
    public $url;
    public $stream;


    public function __construct()
    {
        $level = Logger::DEBUG;
        $stream = storage_path('logs/cli-log-' . date('Y-m-d') . '.log');
        parent::__construct($stream, $level);
        $this->setFormatter($this->getCustomFormatter());
    }


    protected function write(array $record)
    {
        $this->setstream();
        $log_append = [
            'log_time' => date('Y-m-d H:i:s'),
            'request_id' => config('REQUEST_ID','')
        ];
        $ret = "";
        $data['log_message'] = $record['message'];
        $log = $this->processRecord(array_merge($log_append,$data));
        $ret .=  $this->getFormatter()->format($log);
        // 调用日志写入方法
        parent::write(['formatted' => $ret]);



    }

    protected function getCustomFormatter()
    {
        return new JsonFormatter();
    }


    public function setStream()
    {
        $stream = storage_path('logs/cli-log-' . date('Y-m-d') . '.log');
        if($this->stream != $stream){
            $this->close();
        }

        if (is_resource($stream)) {
            $this->stream = $stream;
        } elseif (is_string($stream)) {
            $this->url = $stream;
        } else {
            throw new \InvalidArgumentException('A stream must either be a resource or a string.');
        }

    }
}