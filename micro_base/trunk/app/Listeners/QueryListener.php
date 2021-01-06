<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/18
 * Time: 14:17
 */

namespace App\Listeners;


use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Log;

class QueryListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle(QueryExecuted $event)
    {
        config(['REQUEST_ID'=>rand(100000000,999999999)]);
        if(config('app.sql_log')){
            $sql = str_replace("?", "'%s'", $event->sql);

            $log = vsprintf($sql, $event->bindings);

            Log::info($log);
        }
    }
}