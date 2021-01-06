<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/6/28
 * Time: 14:30
 */

namespace Micro\Common\Contract;

use Illuminate\Queue\SerializesModels;

abstract class Event
{
    use SerializesModels;

    public $request;
    public function __construct($request)
    {
        $this->request = $request;

    }


}