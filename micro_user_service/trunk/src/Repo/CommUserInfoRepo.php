<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/31
 * Time: 14:15
 */

namespace Micro\User\Repo;


use Illuminate\Support\Facades\DB;
use Micro\Common\Contract\Repository;
use Micro\Common\Criteria\Criteria;
use Micro\User\Model\CommUserInfo;

class CommUserInfoRepo extends Repository
{
    public function __construct(CommUserInfo $model)
    {
        $this->model = $model;
    }
    //获取用户密码
    public function getUserPassword($id)
    {
        $ret = optional($this->model
            ->select('pass_word')
            ->where('id',$id)
            ->first())
            ->toArray();
        return $ret;
    }
    //根据ID查询用户信息(身份证号不加密)
    public function getUserNo($user_id){
        return optional($this->model
            ->select('id','user_id','user_name','user_type','status','login_name','level_id','merc_id','client_id','create_time',
                'user_tariff_code','crp_nm','crp_id_no','crp_id_no as crp_id_no_raw','account_name','open_bank_name','cash_status','account_no','account_no as account_no_raw','agent_id','regist_address','bank_line_name','bank_reserved_mobile','bank_reserved_mobile as bank_reserved_mobile_raw','level_name','bank_code','headimgurl','gender','user_age','industry')
            ->where('user_id',$user_id)
            ->first())
            ->toArray();
    }
    //更新用户信息
    public function updateUser($user_id,$data)
    {
        return $this->model->where('user_id',$user_id)->update($data);
    }
    //更新密码
    public function updateUserPass($login_name,$data)
    {
        return $this->model->where('login_name',$login_name)->update($data);
    }
    //信息更改
    public function update($id, $attributes)
    {
        return $this->model->where('id',$id)->update($attributes);
    }
    //获取我的用户信息
    public function getMineInfo($user_id)
    {
        $criteria = Criteria::create()
            ->from('comm_user_info')
            ->where('user_id',$user_id);

        return $this->cacheFirst(
            $user_id.'getMineInfo',
            60,
            $criteria,
            [
                'id', 'user_id', 'login_name', 'user_name', 'status', 'level_name', 'headimgurl', 'user_tariff_code', 'bank_code', 'account_no', 'account_name', 'crp_nm', 'crp_id_type', 'crp_id_no', 'bank_reserved_mobile', 'regist_address', 'open_bank_name', 'bank_line_name','gender','user_age','industry','birthday'
            ]);
    }
    public function getCodeByTel($tel)
    {
        $ret = optional($this->model
            ->select('user_id','user_tariff_code')
            ->where('login_name',$tel)
            ->first())
            ->toArray();
        return $ret['user_tariff_code'];
    }

    //根据手机号查询用户信息
    public function getUserByLoginName($tel)
    {
        $ret = optional($this->model
            ->select('id','user_id','status','pass_word','login_name','user_tariff_code')
            ->where('login_name',$tel)
            ->first())
            ->toArray();
        return $ret;
    }
    //根据ID查询用户信息
    public function getUser($user_id){
        return optional($this->model
            ->select('id','user_id','user_name','user_type','status','login_name','level_id','merc_id','client_id','create_time',
                'user_tariff_code','crp_nm','crp_id_no','account_name','open_bank_name','cash_status','account_no','agent_id','parner_id','regist_address','bank_line_name','bank_line_code','bank_reserved_mobile','level_name','bank_code','store_id','headimgurl')
            ->where('user_id',$user_id)
            ->first())
            ->toArray();
    }
    //查询粉丝数量 关注人数
    public function getUserfans($user_id){
        return optional($this->model
            ->select('id','user_id','user_name','total_follow_num','total_fans_num','total_circusee_num')
            ->where('user_id',$user_id)
            ->first())
            ->toArray();
    }
    public function getUserIdByMobile($mobile)
    {
        $ret = optional($this->model
            ->select('user_id','user_name','user_tariff_code')
            ->where('login_name',$mobile)
            ->get())->toArray();
        return $ret;
    }

    //根据用户资费(等级)获取用户列表
    public function getUserListByTariffCode($user_tariff_code){
        return optional($this->model
            ->select('user_id','user_name','user_tariff_code')
            ->where('user_tariff_code',$user_tariff_code)
            ->get())
            ->toArray();
    }

    public function getUserList(){
        DB::table('comm_user_info')
            ->select('id')
            ->orderBy('create_time','desc')
            ->chunk(50,function ($miner){
                $data = optional($miner)->toArray();
                app(TestService::class)->createAccount($data);
            });
    }

    public function getCacheUserInfo($user_id){
        $criteria = Criteria::create()
            ->from('comm_user_info')
            ->where('id',$user_id);
        return $this->cacheFirst(
            $user_id.'getUserInfo',
            60,
            $criteria,
            ['id','user_id','user_name','user_type','status','login_name','level_id','merc_id','client_id','create_time',
                'user_tariff_code','crp_nm','crp_id_no','crp_id_no as crp_id_no_raw','account_name','open_bank_name','cash_status','account_no','account_no as account_no_raw','agent_id','regist_address','bank_line_name','bank_reserved_mobile','bank_reserved_mobile as bank_reserved_mobile_raw','level_name','bank_code','headimgurl']);
    }

    //获取用户手机号
    public function getUserMobile($user_id)
    {
        $criteria = Criteria::create()
            ->from('comm_user_info')
            ->whereIn('id',$user_id);

        return $this->get(
            $criteria,
            [
                'login_name'
            ]);
    }
}