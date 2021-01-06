<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/4
 * Time: 11:58
 */

namespace App\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Micro\Common\Base\Base;

class TaskJob extends Command
{
    protected $signature = 'job {function} {--id=} {--code=} {--amount=}';
    protected $description = 'TaskJob description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(){
        switch ($this->argument('function')){
            case 'realTimeDispatch' :
                $this->realTimeDispatch();
                break;
        }
    }

    //未派订单实时调度,派单
    public function realTimeDispatch(){
        return Base::service()
            ->middle('RealTimeDispatch')
            ->run();
    }
}