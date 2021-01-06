<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/2/7
 * Time: 12:34
 */
namespace App\Handlers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Micro\Common\Base\Base;
use Unirest\Exception;

class SwooleWebSocketServer  {
    public $serv;

    //onHandShake事件回调是可选的
    public function onHandShake(\swoole_http_request $request, \swoole_http_response $response)
    {
        // onHandShake函数必须返回true表示握手成功，返回其他值表示握手失败

        // print_r( $request->header );
        // if (如果不满足我某些自定义的需求条件，那么返回end输出，返回false，握手失败) {
        //    $response->end();
        //     return false;
        // }

        // websocket握手连接算法验证
        $secWebSocketKey = $request->header['sec-websocket-key'];
        $patten = '#^[+/0-9A-Za-z]{21}[AQgw]==$#';
        if (0 === preg_match($patten, $secWebSocketKey) || 16 !== strlen(base64_decode($secWebSocketKey))) {
            $response->end();
            return false;
        }
        echo $request->header['sec-websocket-key'];
        $key = base64_encode(sha1(
            $request->header['sec-websocket-key'] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11',
            true
        ));

        $headers = [
            'Upgrade' => 'websocket',
            'Connection' => 'Upgrade',
            'Sec-WebSocket-Accept' => $key,
            'Sec-WebSocket-Version' => '13',
        ];

        // WebSocket connection to 'ws://127.0.0.1:9502/'
        // failed: Error during WebSocket handshake:
        // Response must not include 'Sec-WebSocket-Protocol' header if not present in request: websocket
        if (isset($request->header['sec-websocket-protocol'])) {
            $headers['Sec-WebSocket-Protocol'] = $request->header['sec-websocket-protocol'];
        }

        foreach ($headers as $key => $val) {
            $response->header($key, $val);
        }

        $response->status(101);
        $response->end();
        echo "connected!" . PHP_EOL;
        return true;


    }


    public function onOpen(\swoole_websocket_server $serv,\swoole_http_request $request)
    {
        Log::info('socket 已连接'.$request->fd);
        Log::info('连接时间'.date('Y-m-d H:i:s'));
        echo "server: handshake success with fd{$request->fd}\n";

        $data = [
            'code' => '0000',
            'message' => '连接成功',
            'data' => [
                'type' => '0010'
//                'params' => [1]
            ],
        ];
        $serv->push($request->fd,json_encode($data));
    }

    public function onMessage(\swoole_websocket_server $serv,\swoole_websocket_frame $frame)
    {

//        $cmd  = json_decode($frame->data)->command;  //S0102;
//        app()->make($cmd)->handle($serv,json_decode($frame->data));
//        $serv->push($frame->fd,'测试测试');
        //$frame->fd，客户端的socket id，使用$server->push推送数据时需要用到
        //$frame->data，数据内容，可以是文本内容也可以是二进制数据，可以通过opcode的值来判断
        //$frame->opcode，WebSocket的OpCode类型，可以参考WebSocket协议标准文档
        //$frame->finish， 表示数据帧是否完整，一个WebSocket请求可能会分成多个数据帧进行发送
//        $obj = json_decode($frame->data);
//        dd($frame);
//        $obj->fd = $frame->fd;
//        $res = app()->make(CafeService::class)->checkCodeStatus($obj);

//        echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";

//        json_decode($frame->data);
        //测试
//        $serv_name = $frame->data;
        Log::info('接受到的数据');
        Log::info('数据类型 |'.gettype($frame->data));
        Log::info(json_encode($frame->data));

        $data = json_decode($frame->data,true);

        Log::info('serv----'.$data['serv']);
        Base::service()
            ->micro('TripService')
            ->with('fd',$frame->fd)
            ->with('data',$data['data'])
            ->middle('socket_'.$data['serv'])
            ->run();

//        $res = [
//            'id' => '1',
//            'user_name' => '测试'
//        ];
//        $data = [
//            'code' => '0000',
//            'message' => '推送成功',
//            'data' => $res,
//        ];
//        $serv->push($frame->fd, json_encode($data));
        Log::info('------------success');
    }

    public function onClose(\swoole_websocket_server $serv,$fd,$reactorId)
//    public function onClose(\swoole_websocket_server $serv,\swoole_websocket_frame $frame)
    {
        Log::info('-------fd'.$fd);
//        Log::info('-------进程ID '.$reactorId);
        Log::info('fd 断开链接,更新数据');
        return Base::service()
            ->micro('TripService')
            ->with('fd',$fd)
            ->middle('socket_SocketBreakLink')
            ->run();
    }

    public function onRequest(\swoole_http_request $request, \swoole_http_response $response)
    {
        Log::info('------------onRequest');

        $params = $request->post;
        Log::info('--------'.json_encode($params));
        
        $fd = $params['fd'];
        unset($params['fd']);
        $data = [
            'code' => '0000',
            'message' => '推送成功',
            'data' => $params,
        ];
        $flag = $this->serv->exist($fd);
        if(!$flag){
            Log::info('socket推送失败 |'.json_encode($data));
            $response->write('0000');
            return ;
        }
        try{
            $this->serv->push($fd,json_encode($data));
            Log::info('------------push');
            $response->write('0000');
        }catch (Exception $e){
            Log::info('推送失败');
        }
        return '0000';
    }
}