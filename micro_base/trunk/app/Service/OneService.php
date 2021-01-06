<?php
namespace App\Service;

use Micro\Common\Base\BaseService;

/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/7/30
 * Time: 15:46
 */

class OneService extends BaseService
{


    public function handle($request)
    {
//        Err('err');
        $request['one'] ='one';
        return $request;
    }


}