<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/2/20
 * Time: 12:04
 */

namespace Micro\Common\Common\Util;


class Python
{
    public static function command($command,$request)
    {
        $request = "--arg=".base64_encode(json_encode($request));
        $index = base_path('python/run.py');
        $output = shell_exec("python3 {$index} {$command} {$request}");
        return $output;
    }
}