<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/21
 * Time: 15:52
 */
namespace App\Console\Commands\TsApi;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Micro\Common\Base\Base;
use Micro\OrderDispatch\Middleware\TimingPointUpload\TimingPointUploadMiddle;

/**
 * @desc 位置定时上传
 * Class PointUploadCommand
 * @package App\Console\Commands\TsApi
 */
class PointUploadCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'point:upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CreateOrderController';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        Log::info('----------位置定时上传----------');
        return Base::service()
            ->middle('PointUploadCommand')
            ->run();
    }
}