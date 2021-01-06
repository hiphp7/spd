<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/31
 * Time: 13:44
 */

namespace Micro\User\Controller;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Micro\Common\Base\Base;
use Micro\Common\Contract\Controller;
use Micro\User\Middleware\CheckEmergencyContactMiddle;
use Micro\User\Middleware\CreateEmergencyContactMiddle;
use Micro\User\Middleware\GetEmergencyListMiddle;
use Micro\User\Middleware\GetTripOrderListMiddle;
use Micro\User\Middleware\SubmitFeedbackMiddle;
use Micro\User\Middleware\UploadFileMiddle;
use Micro\User\Repo\BaseinfoEmergencyContactRepo;
use Micro\User\Repo\CommNoticeRepo;
use Micro\User\Repo\CommUserInfoRepo;
use Micro\User\Repo\CommUserRepo;
use Micro\User\Service\MineService;
use Micro\User\Service\TripOrderService;

class MineController extends Controller
{
    public function getRules()
    {
        return [
            'modifyNickname' => [
                'newName' => 'required'
            ],
            'modifyGender' => [
                'newName' => 'required|desc:性别 0010:男 0020:女 0090未说明'
            ],
            'modifyAge' => [
                'age' => 'required|desc:年龄'
            ],
            'modifyLoginName' => [
                'login_name' => 'required|desc:手机号'
            ],
            'modifyBirthday' => [
                'birthday' => 'required|desc:生日'
            ],
            'modifyIndustry' => [
                'industry' => 'required|desc:行业'
            ],
            'getTripOrderList' => [
                'page' =>'required',
                'pageSize' =>'required',
                'type' => 'desc:类型--0010-->实时订单;0020-->预约订单;0030-->顺风车单'
            ],
            'getTripOrderInfo' => [
                'order_id' =>'required|desc:订单id',
            ],
            'createEmergencyContact' =>[
                'emergency_contact'=>'required|desc:紧急联系人',
                'emergency_contact_phone'=>'required|desc:紧急联系人电话',
                'auto_share'=>'required|desc:自动分享行程-->0010--自动分享--0000--不自动分享',
                'time_slot'=>'desc:时间段',
                'distance'=>'desc:行驶距离',
            ],
            'getEmergencyList' => [
                'page' =>'required',
                'pageSize' =>'required',
            ],
            'getEmergencyInfo' => [
                'id' =>'required|desc:联系人id',
            ],
            'delEmergency' => [
                'id' =>'required|desc:联系人id',
            ],
            'updateEmergency' =>[
                'id' =>'required|desc:联系人id',
                'emergency_contact'=>'required|desc:紧急联系人',
                'emergency_contact_phone'=>'required|desc:紧急联系人电话',
                'auto_share'=>'required|desc:自动分享行程-->0010--自动分享--0000--不自动分享',
                'time_slot'=>'desc:时间段',
                'distance'=>'desc:行驶距离',
            ],
            'submitFeeback' => [
                'basic_info'       => 'desc:问题类型|required',
                'content'       => 'desc:问题描述|required',
                'img'       => 'desc:截图地址',
            ],
            'getBillInfo' => [
                'direction' => 'required|in:0,1,2',
                'page'      => 'required',
                'pageSize'  => 'required'
            ],
            'bindBankCard' => [
                'accountName'       => 'required|min:2|max:6|regex:/([\xe4-\xe9][\x80-\xbf]{2}){2,6}$/|desc:持卡人姓名',  //持卡人姓名
                'accountNo'         => 'required|accountno|desc:银行卡账号',  //银行卡账号
                'bankLeaveMobile'   => 'required|mobile|desc:银行预留手机号手机号',   //银行预留手机号手机号
                'provinceName'      => 'required|desc:开户省名称',    //开户省名称
                'cityName'          => 'required|desc:开户市名称',    //开户市名称
                'openBankName'      => 'required|desc:开户市名称',    //开户市名称,
                'bankCode'          => 'required|desc:开户行简称',    //开户行简称
                'bankLineName'      => 'required|desc:联行号名称',    //联行号名称
                'bankLineCode'      => 'required|desc:联行号id',    //联行号id
                'provinceId'        => 'required|desc:省编码',    //省编码
                'cityId'            => 'required|desc:市编码',     //市编码
            ],
        ];
    }

    /**
     * @desc获取我的用户信息
     * @return string gender 性别 0010:男 0020:女 0090未说明
     * @return string user_age 用户年龄
     * @return string industry 行业
     * @return string birthday 生日
     */
    public function getMineInfo(Request $request)
    {
        $user_id = $request->user()->claims->getId();
        $ret = app('micro-client')
            ->service('Micro\User\Service\MineService')
            ->micro('aaa')
            ->with('user_id',$user_id)
            ->run('getMineInfo');

        return $ret['data'];
    }

    /**
     * @desc 头像上传
     */
    public function uploadHeadImg(CommUserInfoRepo $user,Request $request)
    {
        $user_id = $request->user()->claims->getId();
        Log::info("头像修改:|" . $user_id);


        $ret = Base::service()
            ->with('user_id', $user_id)
            ->setMiddleware([
                UploadFileMiddle::class,
            ])
            ->run();
        $data = [
            'headimgurl' => strstr($ret['path'],'upload/')
        ];
        $user->updateUser($user_id,$data);
        return R($data['headimgurl']);
    }

    /**
     * @desc 修改昵称
     */
    public function modifyNickname(CommUserRepo $user,Request $request)
    {
        $user_id = $request->user()->claims->getId();
        $re = $user->update($user_id,['user_name' => $request->input('newName')]);
        if (!$re) {
            Err('修改失败，请稍后再试');
        }
        return '0000';
    }

    /**
     * @desc 修改性别
     */
    public function modifyGender(CommUserRepo $user,Request $request)
    {
        $user_id = $request->user()->claims->getId();
        $re = $user->update($user_id,['gender' => $request->input('gender')]);
        if (!$re) {
            Err('修改失败，请稍后再试');
        }
        return '0000';
    }

    /**
     * @desc 修改年龄
     */
    public function modifyAge(CommUserRepo $user,Request $request)
    {
        $user_id = $request->user()->claims->getId();
        $re = $user->update($user_id,['age' => $request->input('age')]);
        if (!$re) {
            Err('修改失败，请稍后再试');
        }
        return '0000';
    }

    /**
     * @desc 修改生日
     */
    public function modifyBirthday(CommUserRepo $user,Request $request)
    {
        $user_id = $request->user()->claims->getId();
        $re = $user->update($user_id,['birthday' => $request->input('birthday')]);
        if (!$re) {
            Err('修改失败，请稍后再试');
        }
        return '0000';
    }

    /**
     * @desc 修改手机号
     */
    public function modifyLoginName(CommUserRepo $user,Request $request)
    {
        $user_id = $request->user()->claims->getId();
        $re = $user->update($user_id,['login_name' => $request->input('login_name')]);
        if (!$re) {
            Err('修改失败，请稍后再试');
        }
        return '0000';
    }

    /**
     * @desc 修改行业
     */
    public function modifyIndustry(CommUserRepo $user,Request $request)
    {
        $user_id = $request->user()->claims->getId();
        $re = $user->update($user_id,['industry' => $request->input('industry')]);
        if (!$re) {
            Err('修改失败，请稍后再试');
        }
        return '0000';
    }

    /**
     * @desc 获取行程订单列表（乘客） 分为历史行程订单和行程中订单
     * @param Request $request
     * @return string id ID
     * @return string dep_are 上车地点
     * @return string dep_time 上车时间
     * @return string dest_area 下车地点
     * @return string dest_time 下车时间
     * @return string order_type 类型--0010-->实时订单;0020-->预约订单;0030-->顺风车单
     * @return string status 状态- 0000 :订单完成,已支付 0010 :司机接单 0020 :接到乘客,行程中 0030:行程结束,报价成功,等待支付  0040:报价失败,不可支付 0050: 发起收款
     * @return array
     */
    public function getTripOrderList(Request $request){
        $user_id = $request->user()->claims->getId();
        Log::info('获取行程订单列表----'.$user_id);
        $type = $request->input('type');
        $page = $request->input('page');
        $pageSize = $request->input('pageSize');
        $ret = Base::service()
            ->with('user_id',$user_id)
            ->with('type',$type)
            ->with('page',$page)
            ->with('pageSize',$pageSize)
            ->setMiddleware([
                GetTripOrderListMiddle::class
            ])
            ->run();
        return  $ret;
    }

    /**
     * @desc 获取行程订单信息（乘客）
     * @param Request $request
     * @return string id ID
     * @return string passenger_id 乘客ID
     * @return string driver_id 司机ID
     * @return string driver_name 机动车驾驶员姓名
     * @return string dep_longitude 预计出发地点经度 单位：1*10-6度
     * @return string dep_latitude 预计出发地点纬度 单位：1*10-6度
     * @return string dep_are 上车地点
     * @return string dep_time 上车时间
     * @return string dest_longitude 预计目的地经度 单位：1*10-6度
     * @return string dest_latitude 预计目的地纬度 单位：1*10-6度
     * @return string dest_area 下车地点
     * @return string dest_time 下车时间
     * @return string drive_mile 载客里程
     * @return string drive_time 载客时间
     * @return string fact_price 实收金额 单位：元
     * @return string pay_state 结算状态 0000已结算 0010：未结算 0020：未知
     * @return string pay_time 乘客结算时间
     * @return string sum_cost 订单总价
     * @return string start_cost 起步价
     * @return string time_cost 时间费用
     * @return string distance_cost 里程费用
     * @return string status 订单状态 0000 :订单完成,已支付 0010 :司机接单 0020 :接到乘客,行程中 0030:行程结束,报价成功,等待支付  0040:报价失败,不可支付
     * @return string passenger_name 乘客用户名
     * @return string passenger_headimgurl 乘客头像
     * @return string driver_name 司机用户名
     * @return string driver_headimgurl 司机头像
     * @return array
     */
    public function getTripOrderInfo(Request $request){
        $user_id = $request->user()->claims->getId();
        $order_id = $request->input('order_id');
        Log::info('获取行程订单列表----'.$order_id);

        return Base::service(TripOrderService::class)
            ->with('user_id',$user_id)
            ->with('order_id',$order_id)
            ->run('getTripOrderInfo');
    }

    /**
     * @desc 添加紧急联系人
     */
    public function createEmergencyContact(Request $request)
    {
        $user_id = $request->user()->claims->getId();
        Log::info('添加紧急联系人-------'.$user_id);
        $ret=Base::service()
            ->with('user_id',$user_id)
            ->with('emergency_contact',$request->input('emergency_contact'))
            ->with('emergency_contact_phone',$request->input('emergency_contact_phone'))
            ->with('auto_share',$request->input('auto_share'))
            ->with('time_slot',$request->input('time_slot'))
            ->with('distance',$request->input('distance'))
            ->setMiddleware([
                CheckEmergencyContactMiddle::class,
                CreateEmergencyContactMiddle::class,
            ])
            ->run();
        return $ret;
    }

    /**
     * @desc 紧急联系人列表
     * @return string emergency_contact 紧急联系人
     * @return string emergency_contact_phone 紧急联系人电话
     * @return string auto_share 自动分享行程-->0010--自动分享--0000--不自动分享
     * @return string time_slot 时间段
     * @return string distance 行驶里程
     */

    public function getEmergencyList(Request $request)
    {
        $user_id = $request->user()->claims->getId();
        Log::info('获取联系人列表');
        $ret=Base::Service()
            ->with('user_id',$user_id)
            ->with('page',$request->input('page'))
            ->with('pageSize',$request->input('pageSize'))
            ->setMiddleware([
                GetEmergencyListMiddle::class,
            ])
            ->run();
        return $ret;
    }

    /**
     * @desc 紧急联系人详情
     * @return string emergency_contact 紧急联系人
     * @return string emergency_contact_phone 紧急联系人电话
     * @return string auto_share 自动分享行程-->0010--自动分享--0000--不自动分享
     * @return string time_slot 时间段
     * @return string distance 行驶里程
     */

    public function getEmergencyInfo(BaseinfoEmergencyContactRepo $repo ,Request $request)
    {
        $user_id = $request->user()->claims->getId();
        Log::info('获取联系人列表');
        return $repo->find($request->input('id'));
    }

    /**
     * @desc 删除紧急联系人
     */

    public function delEmergency(BaseinfoEmergencyContactRepo $repo ,Request $request)
    {
        $user_id = $request->user()->claims->getId();
        Log::info('获取联系人列表---'.$request->input('id'));
        $repo->delete($request->input('id'));

        return '0000';
    }

    /**
     * @desc 修改紧急联系人
     */
    public function updateEmergency(BaseinfoEmergencyContactRepo $repo ,Request $request)
    {
        $user_id = $request->user()->claims->getId();
        Log::info('获取联系人列表---'.$request->input('id'));
        $param=[
            'emergency_contact'=>$request['emergency_contact'],
            'emergency_contact_phone'=>$request['emergency_contact_phone'],
            'auto_share'=>$request['auto_share'],
            'time_slot'=>$request['time_slot'],
            'distance'=>$request['distance'],
        ];
        $repo->update($request->input('id'),$param);

        return '0000';
    }

    /**
     * @desc 获取行业类别
     */
    public function getIndustryCategory()
    {
        return [
            '互联网','建筑','文化传媒','金融','教育','医疗'
        ];
    }

    /**
     * @desc 提交反馈（乘客）
     * @param Request $request
     */
    public function submitFeeback(Request $request){
        $user_id = $request->user()->claims->getId();
        Log::info('提交反馈----'.$user_id);
        $ret=Base::service()
            ->with('user_id',$user_id)
            ->with('basic_info',$request->input('basic_info'))
            ->with('content',$request->input('content'))
            ->with('img',$request->input('img'))
            ->setMiddleware([
                SubmitFeedbackMiddle::class,
            ])
            ->run();

        return $ret;
    }

    /**
     * @desc 获取常见问题列表
     */
    public function getComProblem(CommNoticeRepo $repo){

        $problem=$repo->getComProblem();
        return $problem;
    }

    /**
     * @desc 我的资产
     */
    public function getBalance(Request $request)
    {
        $user_id = $request->user()->claims->getId();
        Log::info("用户资产查询:|" . $user_id);

        return Base::service(MineService::class)
            ->with('acct_id',1)
            ->with('user_id',$user_id)
            ->run('getBalance');
    }

    /**
     * @desc 用户账单查询接口  0 全部账单 1支出 2收入
     */
    public function getBillInfo(Request $request)
    {
        $user_id = $request->user()->claims->getId();
        Log::info("用户账单查询:|" . $user_id);

        return app('micro-client')
            ->service('Micro\Finance\Service\BalanceInfoService')
            ->micro('aaa')
            ->with('user_id',$user_id)
            ->with('direction',$request->input('direction'))
            ->with('page',$request->input('page'))
            ->with('pageSize',$request->input('pageSize'))
            ->run('getBookOrderList');
    }

    /**
     * @desc 乘客获取行程中订单
     * @param Request $request
     * @return array
     */
    public function getOnTripOrderByPassengerId(Request $request){
        //1.判断乘客有没有行程中订单
        //2.获取行程中订单

        $passenger_id = $request->user()->claims->getId();
        $passenger_id= '1142889689825594113';
        $ret = app('micro-client')
            ->micro('GetTripOrderListService')
            ->service('Micro\\OrderDispatch\\Service\\GetTripOrderListService')
            ->with('passenger_id',$passenger_id)
            ->run('getOnTripOrderByPassengerId');

        if(!$ret['data']) return [];
        $code = Base::code('redis_db.order_realtime');
        $redis = getConnections([
            'name' => 'default_'.$code['property2'],
            'database' => $code['property2']
        ]);

        $orderInfo = $redis->exists(env('QUEUE_NAME')."_in_trip_order:".$ret['data']['order_id'].'1');

        //判断司机是否在行程中,如果获取最后一笔订单
        if(!$orderInfo) return[];
        return $ret['data'];
    }

    /**
     * @desc 银行卡绑定
     */
    public function bindBankCard(Request $request)
    {
        $user_id = $request->user()->claims->getId();

        Log::info('银行卡绑定:|' .$user_id);

        $ret =  Base::service()
            ->with('user_id', $user_id)
            ->with('accountName', $request->input('accountName'))
            ->with('accountNo', $request->input('accountNo'))
            ->with('bankLeaveMobile', $request->input('bankLeaveMobile'))
            ->with('provinceName', $request->input('provinceName'))
            ->with('cityName', $request->input('cityName'))
            ->with('openBankName', $request->input('openBankName'))
            ->with('bankCode', $request->input('bankCode'))
            ->with('bankLineName', $request->input('bankLineName'))
            ->with('bankLineCode', $request->input('bankLineCode'))
            ->with('provinceId', $request->input('provinceId'))
            ->with('cityId', $request->input('cityId'))
            ->with('auth_type','10')
            ->setMiddleware([
//                CheckBankCardMiddle::class,
                UpdateBankcardMiddle::class,
            ])
            ->run();
        if($ret['data'] != 1) Err('操作失败:1001');
        return '0000';
    }
}