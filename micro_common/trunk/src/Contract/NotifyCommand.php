<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/7/8
 * Time: 14:07
 */

namespace Micro\Common\Contract;


use Illuminate\Console\Command;

abstract class NotifyCommand extends Command
{

    public function handle()
    {
        while (true){
            $message = $this->getMessage();
            if($message){
                $this->process($message);

            }
        }
    }
    abstract public function getConnection();
    abstract public function getNotify();
    abstract public function process($request);

    public function getMessage()
    {

        $timeout = 0;
        $client = app('redis')->connection($this->getConnection());
        $message = $client->blpop("notify_list_".$this->getNotify(),$timeout);
        echo $timeout;
        if($message){
            return json_decode($message[1],true);
        }else{
            return false;
        }
    }
}