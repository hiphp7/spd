<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/2/21
 * Time: 13:54
 */

namespace App\Handlers;



use App\Common\Criteria\Criteria;
use App\Modules\Base\Base;
use App\Modules\Base\Repository\BaseEventRepo;
use App\Modules\Base\Repository\BaseJobRepo;
use App\Modules\Base\Repository\BaseMiddlewareRepo;
use App\Modules\Base\Repository\BaseServiceRepo;
use App\Modules\Base\Repository\CodeMasterRepo;
use App\Modules\Finance\Repository\AcctBookingTempletRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SwooleHttpServer
{
    public $serv;
    public $config=[];

    public function onStart()
    {
        echo "onStart";
        $this->loadConfig();
    }

    /**
     * @param $request
     * @param $response
     * @desc 接收到swoole_http 请求
     */
    public function onRequest($request,$response)
    {
        $this->devReload($request);
        $http_request = $this->parseRequest($request);
        $http_response = app()->handle($http_request);
        $this->makeResponse($response,$http_response);
    }

    /**
     *
     */
    public function onTask(\swoole_server $serv, $task_id, $from_id, $data)
    {
        $data = json_decode($data,true);
        $ret = Base::service($data['service'])
            ->pass($data['request'])
            ->middle($data['middle'])
            ->before($data['before'])
            ->after($data['after'])
            ->run();
        $serv->finish(json_encode($ret));
    }

    public function onFinish(\swoole_server $serv, $task_id, $data)
    {
        echo "Task#$task_id finished, result =".$data.PHP_EOL;
    }


    /**
     * @param \swoole_http_request $request
     * @return Request
     * @desc 转换swoole_reuest->lumen_request
     */
    public function parseRequest(\swoole_http_request $request)
    {
        $get     = isset($request->get) ? $request->get : array();
        $post    = isset($request->post) ? $request->post : array();
        $cookie  = isset($request->cookie) ? $request->cookie : array();
        $server  = isset($request->server) ? $request->server : array();
        $header  = isset($request->header) ? $request->header : array();
        $files   = isset($request->files) ? $request->files : array();
        $fastcgi = array();
        $new_server = array();
        foreach ($server as $key => $value) {
            $new_server[strtoupper($key)] = $value;
        }
        foreach ($header as $key => $value) {
            $new_server['HTTP_' . strtoupper($key)] = $value;
        }

        $content = $request->rawContent() ?: null;

        $http_request = new Request(
            $get, $post, $fastcgi, $cookie, $files, $new_server, $content
        );

        return $http_request;
    }


    /**
     * @param $response
     * @param $http_response
     * @desc lumen_response -> swoole_response
     */
    public function makeResponse($response,$http_response)
    {
        // status
        $response->status($http_response->getStatusCode());
        // headers
        foreach ($http_response->headers->allPreserveCase() as $name => $values) {
            foreach ($values as $value) {
                $response->header($name, $value);
            }
        }
        // content
        $content = $http_response->getContent();

        // send content
        $response->end($content);
    }

    public function devReload($request)
    {
//        if(config('app.env') == 'development'){
//
//            app('swoole')->serv->reload();
//            $this->loadConfig();
//        }
        $act = $request->get['swoole_http_server_command'] ?? '';
        if($act == 'reload'){
            app('swoole')->serv->reload();
            $this->loadConfig();
        }
    }


    public function loadConfig()
    {
        $this->loadBaseService();
        $this->loadBaseMiddleware();
        $this->loadBaseEvent();
        $this->loadBaseJob();
        $this->loadCodeMaster();
        $this->loadAcctBookingTemplate();

        //还可以加载其他的配置
    }
    public function setConfig($table,$value)
    {
        app('swoole')->config[$table] = $value;
    }

    public function getConfig($key)
    {
        return Arr::get(app('swoole')->config,$key);
    }

    public function getTableConfig($table,$key)
    {
        $key = str_replace(".","_",$key);
        $config = $table.'.'.$key;
        return $this->getConfig($config);
    }
    public function loadBaseService()
    {

        try{
            $ret =[];
            $services = app(BaseServiceRepo::class)->get(
                Criteria::create()->where('status',1)
            );
            foreach ($services as $key=>$value){
                $k = str_replace(".","_",$value['service']);
                $ret[$k] = $value;
            }
            $this->setConfig('base_service',$ret);
        }catch (\Exception $ex){}
    }




    public function loadBaseMiddleware()
    {
        try{
            $ret = [];
            $middlewares = app(BaseMiddlewareRepo::class)->get(
                Criteria::create()->where('status',1)
                ->orderBy('order','ASC')
            );
            foreach ($middlewares as $key=>$value){
                $k = str_replace(".","_",$value['middle']);
                $ret[$k][$value['order']] = $value;
            }
            $this->setConfig('base_middleware',$ret);

        }catch (\Exception $ex){}
    }

    public function loadBaseEvent()
    {
        try{
            $ret = [];
            $before_events = app(BaseEventRepo::class)->get(
                Criteria::create()->where('status',1)
                ->where('type','10')
                ->orderBy('status','ASC')
            );
            foreach($before_events as $key=>$value){
                $k = str_replace(".","_",$value['event']);
                $ret['before'][$k][$value['order']] = $value;
            }

            $after_events = app(BaseEventRepo::class)->get(
                Criteria::create()->where('status',1)
                    ->where('type','20')
                    ->orderBy('status','ASC')
            );
            foreach($after_events as $key=>$value){
                $k = str_replace(".","_",$value['event']);
                $ret['after'][$k][$value['order']] = $value;
            }

            $this->setConfig('base_event',$ret);


        }catch (\Exception $ex){}
    }


    public function loadBaseJob()
    {
        try{
            $ret = [];
            $jobs = app(BaseJobRepo::class)->get(
                Criteria::create()->where('status',1)
                    ->orderBy('order','ASC')
            );
            foreach ($jobs as $key=>$value){
                $k = str_replace(".","_",$value['job']);
                $ret[$k][$value['order']] = $value;
            }
            $this->setConfig('base_job',$ret);

        }catch (\Exception $ex){}
    }


    public function loadCodeMaster()
    {
        try{
            $ret = [];
            $masters = app(CodeMasterRepo::class)->get();
            foreach ($masters as $key =>$value){
                $k1 = str_replace(".","_",$value['code']);
                $k2 = str_replace(".","_",$value['code_key']);
                $ret[$k1][$k2] = $value;
            }
            $this->setConfig('common_code_master',$ret);

        }catch (\Exception $ex){}
    }

    public function loadAcctBookingTemplate()
    {
        try{
            $ret = [];
            $tempaltes = app(AcctBookingTempletRepository::class)->get(
                Criteria::create()->where('use_status',1)
                ->orderBy('voucher_batch_id')
            );
            foreach ($tempaltes as $key=>$value){
                $k = str_replace(".","_",$value['voucher_code']);
                $ret[$k][$value['voucher_batch_id']] = $value;
            }
            $this->setConfig('acct_booking_template',$ret);

        }catch (\Exception $ex){}
    }



}