<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/16
 * Time: 14:59
 */

namespace Micro\Common\Base\Setup;


use Micro\Common\Contract\Middleware;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * @desc 数据初始化
 * Class BaseDataInsert
 * @package Micro\Common\Base\Setup
 */
class BaseDataInsert extends Middleware
{
    public function __construct()
    {

    }

    public function handle($request, Closure $next)
    {
        try{
            $this->insertCommBankDb();
            $this->insertCommSupportBankInfo();
            echo 'DB 初始化表数据完毕'.PHP_EOL;
            return $next($request);
        }catch (\Exception $e){
            Log::info('错误信息----- | '.json_encode($e));
            Err('初始化表数据完毕 初始化错误');
        }
    }

    /**
     * @desc 初始化表数据
     * @return mixed
     */
    public function insertCommBankDb(){
        return DB::unprepared(file_get_contents(base_path('public/Data/sql/comm_bank_db.sql')));
    }

    /**
     * @desc 银行表
     * @return mixed
     */
    public function insertCommSupportBankInfo(){
        return DB::unprepared(file_get_contents(base_path('public/Data/sql/comm_support_bank_info.sql.sql')));
    }
}