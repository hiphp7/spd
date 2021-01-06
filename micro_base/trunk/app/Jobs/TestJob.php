<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/14
 * Time: 16:45
 */

namespace App\Jobs;


use Illuminate\Support\Facades\Log;

class TestJob extends Job
{
    public function handle(){
        Log::info('test Job ');
    }
}