<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/12/4
 * Time: 11:27
 */

namespace Micro\User\Controller;


use Illuminate\Http\Request;
use Micro\Common\Base\Base;
use Micro\Common\Contract\Controller;
use Micro\User\Service\ShareService;

class ShareController extends Controller
{
    public function getRules()
    {
        return [
            'getShareInfo' => [
                'id' => 'desc:分享ID',
                'type' => 'required|desc:分享类型 0010乘客分享注册 0020司机分享注册 0030城际顺风车分享',
                'user_id' => '',
                'remark' => '',
            ],
            'webShare' => [
                'url' => 'required|desc:分享地址'
            ],
        ];
    }

    /**
     * @desc 获取分享信息
     * @param $request
     * @return mixed
     */
    public function getShareInfo(Request $request)
    {
        $request->setTrustedProxies(array('172.16.50.22'));
        $ip = $request->getClientIp();
        return Base::service(ShareService::class)
            ->with('ip', $ip)
            ->with('id', $request->input('id'))
            ->with('user_id', $request->input('user_id'))
            ->with('remark', $request->input('remark'))
            ->with('kind_of', $request->input('type'))
            ->run('getShareInfo');
    }

    /**
     * @desc 二次分享数据
     */
    public function webShare(Request $request){
        return Base::service(ShareService::class)
            ->with('url', $request->input('url'))
            ->run('webShare');
    }
}