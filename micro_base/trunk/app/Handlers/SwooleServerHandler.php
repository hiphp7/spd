<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/2/7
 * Time: 09:40
 */
namespace App\Handlers;

use App\Modules\Base\Base;
use App\Modules\Base\BaseService;

class SwooleServerHandler {

    public $fds = [];
    public $serv = '';
    public $fd = '';
    public $from_id = '';
    public function onStart(\swoole_server $serv)
    {

    }
    public function onConnect(\swoole_server $serv, $fd, $reactorId)
    {
        $this->fds[$fd] = $fd;

    }

    public function onReceive(\swoole_server $serv,$fd, $from_id, $data )
    {
        $this->serv = $serv;
        $this->fd = $fd;
        $this->from_id = $from_id;
        $response = $this->process(json_decode($data,true));
        $serv->send( $fd,json_encode($response) );
    }
    public function onClose(\swoole_server $serv ,$fd ,$from_id)
    {

    }

    public function onTask(\swoole_server $serv, $task_id, $from_id, $data)
    {

        $ret = Base::service($data['service']??BaseService::class)
            ->pass($data['request']??[])
            ->middle($data['middle']??'')
            ->run();
        $serv->finish(json_encode($ret));
    }

    public function onFinish(\swoole_server $serv, $task_id, $data)
    {
        echo "Task#$task_id finished, result =".$data.PHP_EOL;
    }


    public function process($data)
    {
        $task = $data['task'] ?? false;
        if($task){
            $this->serv->task($data);
            return ['message'=>'task dispatch'];
        }
        $request = $data['request'] ?? [];
        $request['swoole'] = [
            'serv' =>$this->serv,
            'fd' => $this->fd,
            'fds' => $this->fds,
            'from_id' => $this->from_id
        ];
        try{
            $response = Base::service($data['service'] ?? BaseService::class)
                ->pass($request)
                ->middle($data['middle'] ?? '')
                ->run();
        }catch (\Exception $ex){
            $response = $ex->getMessage();
        }
        if(isset($response['swoole'])){
            unset($response['swoole']);
        }


        return $response;
    }



}