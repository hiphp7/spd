<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/3
 * Time: 10:22
 */
namespace Micro\Push\Service;

use Illuminate\Support\Facades\Log;
use Micro\Common\Base\BaseService;
use Micro\Common\Common\Util\JPush;
use Micro\Sms\Repository\CommPushTempletRepo;
use Micro\Sms\Repository\CommSmsRepo;
use Micro\Sms\Repository\SmsCommPushRecordRepo;


class JPushService extends BaseService
{
    public $sms;
    public $temp;
    public $msg;
    public function getRules(){

    }

    public function __construct(CommSmsRepo $sms,CommPushTempletRepo $temp,SmsCommPushRecordRepo $msg)
    {
        $this->sms = $sms;
        $this->temp = $temp;
        $this->msg = $msg;
    }

    public function jpush($request){
        Log::info('异步消息推送');
        $param  = $request['param'];

        $message = $this->pushMessage($request,$param);

        $data = [
            'id'              => ID(),
            'process_id_from' => 'app',
            'process_id_to'   => $request['target'],
            'business_code'   => $message['templet_id'],
            'title'           => $message['title'],
            'content'         => $message['message'],
            'type'            => '0010', //消息类型 0010->消息 0020->通知
            'status'          => '0010', //查看状态 0010->未查看 0020->已查看
            'msg_type'        => $param['type'],
            'create_time'     => date('Y-m-d H:i:s'),
            'create_by'       => 'system',
            'update_time'     => date('Y-m-d H:i:s'),
            'update_by'       => 'system',
        ];

        $ret = $this->msg->insert($data);

        if($ret){
            $arr = [
                'dataid' => $data['id'],
                'type' => $param['type'],
                'path' => $param['path']
            ];

            $client = new JPush();
            return $client->singlePush($data['process_id_to'],$data['title'],$data['content'],$arr);

        }else{
            Log::info('消息添加失败'.json_encode($data));
        }
    }

    //推送消息  消息添加
    public function pushMessage($request,$param)
    {
        $templet = $this->getSmsTemplet($param['code']);

        $message = $this->getMessage($request,$templet['content']);

        $ret['templet_id'] = $templet['templet_id'];
        $ret['message'] = $message;
        $ret['title'] = $templet['title'];

        return $ret;
    }

    //获取短信模版
    public function getSmsTemplet($code)
    {
        return $this->temp->getSmsTemplet($code);
    }

    /**
     * 根据模板获取消息
     */
    public function getMessage($request,$templet)
    {
        $pattern = '/[{](.*?)[}]/';
        preg_match_all($pattern,$templet,$matches);

        $res = [];
        foreach($matches[1] as $key =>$value){

            $value = trim($value);
            $funName = "get".$value;

            $res[$value]=$this->$funName($request);
        }

        foreach($res as $k=>$v){
            $pattern = '{'. $k .'}';
            $templet = str_replace($pattern,$v,$templet);
        }

        return $templet;
    }

    //获取乘客手机后4位
    public function getLastNum($request){
        $length = config('const_sms.CODELEN');
        $code = rand(pow(10,($length-1)), pow(10,$length)-1);
        $this->code = $code;
        return $code;
    }
}