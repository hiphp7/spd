<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/30
 * Time: 10:31
 */

namespace Micro\User\Controller;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Micro\Common\Base\Base;
use Micro\Common\Contract\Controller;
use Micro\User\Middleware\CheckGetTimeMiddle;
use Micro\User\Middleware\CheckOrderMiddle;
use Micro\User\Middleware\CheckToUserIdMiddle;
use Micro\User\Middleware\getChatContentMiddle;
use Micro\User\Middleware\getChatListMiddle;
use Micro\User\Middleware\getMessageListMiddle;
use Micro\User\Middleware\getUserInfoMiddle;
use Micro\User\Middleware\InsertMessageMiddle;
use Micro\User\Repo\CommPushRecordRepo;
use Micro\User\Service\SystemService;

class SystemInfoController extends Controller
{
    public function getRules()
    {
        return [
            'submitFeeback' => [
                'basic_info'       => 'desc:问题类型|required',
                'content'       => 'desc:问题描述|required',
                'img'       => 'desc:截图地址',
            ],
            'getMessageList' => [
                'page' =>'required',
                'pageSize' =>'required',
                'type' =>'',
            ],
            'getMsgContent' => [
                'id' => 'required'
            ],
            'getChatList' => [
                'page' =>'required',
                'pageSize' =>'required',
            ],
            'testCreateChat' => [
                'user_id'       => 'desc:乘客id|required',
                'driver_id'       => 'desc:司机id|required',
                'order_id'       => 'desc:订单id|required',
            ],
            'getChatContent'=>[
                'page' =>'required',
                'pageSize' =>'required',
                'order_id'       => 'desc:订单id|required',
            ],
            'sendMessage'=>[
                'message' =>'desc:消息内容|required',
                'to_id' =>'desc:接收消息id|required',
                'order_id'       => 'desc:订单id|required',
            ],
            'getAreaBankList' => [
                'headEng'   => 'required',      //银行英文简称
                'stateName' => 'required',      //省名称
                'cityName'  => 'max:10',      //城市名称
//                'keyWord'   => 'min:1',      //关键词搜索
                'page'      => 'required',
                'pageSize'  => 'required'
            ],
            'getCityList' => [
                'provinceId' => 'required'
            ],
        ];
    }

    /**
     * @desc 获取通知列表
     * @param Request $request
     */

    public function getMessageList(Request $request){
        $user_id = $request->user()->claims->getId();
        Log::info('查询消息列表----'.$user_id);
        $ret=Base::Service()
            ->with('user_id',$user_id)
            ->with('page',$request->input('page'))
            ->with('pageSize',$request->input('pageSize'))
            ->with('type',$request->input('type'))
            ->setMiddleware([
                getUserInfoMiddle::class,
                getMessageListMiddle::class
            ])
            ->run();
        return $ret;
    }

    /**
     * @desc 查询通知内容
     */
    public function getMsgContent(CommPushRecordRepo $record,Request $request)
    {
        return $record->find($request->input('id'),['id','title','content','create_time']);
    }

    /**
     * @desc 查询消息列表
     * @return string user_id 乘客id
     * @return string driver_id 司机id
     * @return string order_id 订单id
     * @return string update_time 消息最后发送时间
     * @return string user_name 司机用户名
     * @return string headimgurl 司机头像
     * @return string unread 未读消息数量
     * @return string last_message 最后一条消息内容
     * @return array
     */
    public function getChatList(Request $request){
        $user_id = $request->user()->claims->getId();
        Log::info('获取消息列表----'.$user_id);
        $ret=Base::Service()
            ->with('user_id',$user_id)
            ->with('page',$request->input('page'))
            ->with('pageSize',$request->input('pageSize'))
            ->setMiddleware([
                getChatListMiddle::class,
            ])
            ->run();
        return $ret;
    }

    /**
     * @desc 查询消息列表
     * @return string from_id 发送人id
     * @return string to_id 接收人id
     * @return string order_id 订单id
     * @return string create_time 消息发送时间
     * @return string user_name 发送用户名
     * @return string headimgurl 发送用户头像
     * @return string position 区位1->左2->右
     * @return array
     */
    public function getChatContent(Request $request){
        $user_id = $request->user()->claims->getId();
        Log::info('获取消息列表----'.$user_id.'-------'.$request->input('order_id'));
        $ret=Base::Service()
            ->with('user_id',$user_id)
            ->with('page',$request->input('page'))
            ->with('pageSize',$request->input('pageSize'))
            ->with('order_id',$request->input('order_id'))
            ->setMiddleware([
                CheckGetTimeMiddle::class,
                getChatContentMiddle::class,
            ])
            ->run();
        return $ret;
    }

    /**
     * @desc 发送消息
     * @param Request $request
     */

    public function sendMessage(Request $request){
        $user_id = $request->user()->claims->getId();
        Log::info('发送消息----'.$user_id);
        $ret=Base::Service()
            ->with('user_id',$user_id)
            ->with('message',$request->input('message'))
            ->with('order_id',$request->input('order_id'))
            ->with('to_id',$request->input('to_id'))
            ->setMiddleware([
                getUserInfoMiddle::class,
                CheckToUserIdMiddle::class,
                CheckOrderMiddle::class,
                InsertMessageMiddle::class,
            ])
            ->run();
        return $ret;
    }

    /**
     * @desc 根据城市,银行  获取银联列表
     * @param string headEng 银行英文简称
     * @param string stateName 省名称
     * @param string cityName 城市名称
     * @param string keyWord 关键词搜索
     */
    public function getAreaBankList(Request $request)
    {
        return Base::service(SystemService::class)
            ->with('headEng',$request->input('headEng'))
            ->with('stateName',$request->input('stateName'))
            ->with('cityName',$request->input('cityName'))
//            ->with('keyWord',$request['keyWord'])
            ->with('page',$request->input('page'))
            ->with('pageSize',$request->input('pageSize'))
            ->run('getAreaBankList');
    }

    /**
     * @desc 获取银行信息接口
     * comm support bank info
     */
    public function getSupportBankList()
    {
        return Base::service(SystemService::class)
            ->run('getSupportBankList');
    }

    /**
     * @desc 获取省
     */
    public function getProvinceList()
    {
        return Base::service(SystemService::class)
            ->run('getProvinceList');
    }

    /**
     * @desc 获取市
     */
    public function getCityList(Request $request)
    {
        return Base::service(SystemService::class)
            ->with('provinceId',$request->input('provinceId'))
            ->run('getCityList');
    }
}