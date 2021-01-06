<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/11/22
 * Time: 9:25
 */

namespace Micro\User\Controller;


use Illuminate\Http\Request;
use Micro\Common\Base\Base;
use Micro\Common\Contract\Controller;
use Micro\User\Service\UserService;

class UserController extends Controller
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    /**
     * @desc 分享二维码
     */
    public function generateCode(Request $request)
    {
        $userId = $request->user()->claims->getId();
        return Base::service(UserService::class)
            ->with('userId',$userId)
            ->run('generateCode');
    }
}