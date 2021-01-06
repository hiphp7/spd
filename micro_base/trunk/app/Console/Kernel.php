<?php

namespace App\Console;

use App\Console\Commands\SwooleCommand;
use App\Console\Commands\TaskJob;
use App\Console\Commands\TsApi\PointUploadCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use Micro\Common\Base\Base;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SwooleCommand::class,
        PointUploadCommand::class,  //位置定时上传
        TaskJob::class
    ];


    /**
     * https://laravelacademy.org/post/235.html
     *Schedule $schedule->cron(‘* * * * *’); 在自定义Cron调度上运行任务
    ->everyMinute(); 每分钟运行一次任务
    ->everyFiveMinutes(); 每五分钟运行一次任务
    ->everyTenMinutes(); 每十分钟运行一次任务
    ->everyThirtyMinutes(); 每三十分钟运行一次任务
    ->hourly(); 每小时运行一次任务
    ->daily(); 每天凌晨零点运行任务
    ->dailyAt(‘13:00’); 每天13:00运行任务
    ->twiceDaily(1, 13); 每天1:00 & 13:00运行任务
    ->weekly(); 每周运行一次任务
    ->monthly(); 每月运行一次任务
     */
    protected function schedule(Schedule $schedule)
    {
        //轨迹定时上传
//        $schedule->command('product:flash_lottery')
//            ->everyMinute();

        $KernelJobCommandsJson = $this->fileRead();
        $KernelJobCommands = json_decode($KernelJobCommandsJson,true);
        if(isset($KernelJobCommands)&&count($KernelJobCommands)>0){
            $scheduleList = $KernelJobCommands;
        }else{
            //从数据库读取需要执行的定时任务
            $middle = "KernelJobCommands";
            $ret =  Base::service()->middle($middle)->run();
            $scheduleList = $ret["scheduleList"] ?? [];
        }
        foreach($scheduleList as $key => $value) {
            $do = $value['do'];
            $command = $value['command'];
            $time = $value['time'];
            $day = $value['day'] ?? '';
            if ($day) {
                $schedule->command($command)
                    ->$do($day, $time);
            } elseif ($time) {
                $schedule->command($command)
                    ->$do($time);
            } else {
                $schedule->command($command)
                    ->$do();
            }
        }

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
