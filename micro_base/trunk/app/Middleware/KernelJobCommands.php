<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/21
 * Time: 15:47
 */

namespace App\Middleware;


use Closure;
use Illuminate\Support\Facades\Log;
use Micro\Common\Base\Base;
use Micro\Common\Contract\Middleware;

class KernelJobCommands extends Middleware
{
    public function handle($request, Closure $next)
    {
        Log::info("KernelJobCommands.处理要跑的定时任务");
        $kernelJobCommands = Base::code('KernelJobCommands');
        Log::info(json_encode($kernelJobCommands));
        //property2 = do
        //property3 = command
        //property4 = time   可为空
        $scheduleList = [];
        foreach($kernelJobCommands as $k=>$v){
            $scheduleList[$k]['do'] = $v['property2'];
            $scheduleList[$k]['command'] = $v['property3'];
            $scheduleList[$k]['time'] = $v['property4'];
            $scheduleList[$k]['day'] = $v['property5'];
        }
        $request['scheduleList'] = $scheduleList;
        if(count($request['scheduleList'])>0){
            $json = json_encode($request['scheduleList']);
            $this->fileWrite($json);
        }
        return $next($request);
    }

    protected function fileWrite($json)
    {
        $filed=base_path().'/public'.'/KernelJobCommands.php';
        $file = fopen($filed,'w');
        fwrite($file,$json);
        fclose($file);
        return $json;
    }

    protected function fileRead()
    {
        $filed=base_path().'/public'.'/KernelJobCommands.php';
        if(file_exists($filed)){
            $file = fopen($filed,'r');
            $json = fread($file,fileSize($filed));
            fclose($file);
            return $json;
        }else{
            return null;
        }
    }
}