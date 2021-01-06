<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/8/2
 * Time: 13:28
 */

namespace App\Listeners;


class DemoListener
{
    public function handle($events)
    {
        var_dump($events);
    }
}