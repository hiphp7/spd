<?php

namespace App\Controller;

use amap\sdk\AMap;
use amap\sdk\CloudMap\CloudMap;
use amap\sdk\RestAPI\request\DirectionRequest;
use amap\sdk\RestAPI\request\Geo;
use amap\sdk\RestAPI\RestAPI;
use App\Broadcast\BroadcastEvent;
use App\Listeners\DemoListener;
use GuzzleHttp\Client;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Contracts\Broadcasting\Broadcaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jaeger\GHttp;
use Micro\Common\Base\Base;
use Micro\Common\Contract\Controller;
use Micro\Common\Facade\M;
use Micro\Common\Jwt\Token;

use Micro\Common\Tool\Anno;
use Micro\Common\Tool\IdWorker;
use Micro\OrderDispatch\Service\TsApiService;
use Micro\Trip\Model\CXTestMongoModel;
use SwooleTW\Http\Server\Facades\Server;
//use SwooleTW\Http\Table\Facades\SwooleTable;
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/6/28
 * Time: 10:52
 */

class IndexController extends Controller
{


    public function getRules()
    {
        return [
            'getToken' => [
                'id' => 'required|desc:ID'
            ],
            'baseTest' => [
                'dep_longitude' => 'desc:经度',
                'dep_latitude' => 'desc:纬度'
            ]
        ];
    }

    /**
     * 这里是标题注释
     *
     * @desc 描述
     *
     * @param type $arg1 参数一的说明
     * @param mixed $arg2 参数二的说明
     * @param mixed $mixed 这是一个混合类型
     */
    public function index()
    {
        return ID();
        $ret = [];
        $routers = app()->router->getRoutes();
        foreach ($routers as $key => $router) {
            list($controller, $method) = explode('@', $router['action']['uses']);
            $anno = app(Anno::class)->reflection($controller,$method);
            $ret[$key]['method'] = $router['method'];
            $ret[$key]['uri'] = $router['uri'];
            $ret[$key]['title'] = $anno->getTitle();
            $ret[$key]['param'] = $anno->getParam();
            $ret[$key]['return'] = $anno->getReturn();

        }
        return $ret;


    }

    public function indexMockReturn()
    {
        return [
            'date'=>M::date(),
            'number' =>M::number(4),
            'name' => M::cn_name(),
            'price' =>M::price(4,2),
            'email' =>M::email(),
            'url' =>M::url(),
            'mobile' =>M::mobile(),
            'card' =>M::id_card(),
            'city' =>M::city(),
            'array' =>[
                'date'=>M::date(),
                'number' =>M::number(4),
                'name' => M::cn_name(),
                'price' =>M::price(4,2)
            ]
        ];
    }

    /**
     * 这里是标题注释
     *
     * @desc 描述
     *
     * @param type $arg1 参数一的说明
     * @param mixed $arg2 参数二的说明
     * @param mixed $mixed 这是一个混合类型
     * @return string a xxxx string(5)
     * @return number b xxxx number(3)
     * @return string c xxxx cn_name()
     * @return float d  xxx float(4,2)
     * @return boolean e xxxx bool()
     * @return float f xxxx price(3,2)
     * @return string g xxxx date(Y-m-d)
     */
    public function rpcDemo()
    {
//        return '12312';
//        $param = ['aa'=>'bb'];
//        $client = app('micro-thrift');
//        $result = $client->send($param);
//        return $result;

//        Log::info('123123123123');
//        return config('REQUEST_ID');

//        app('events')->listen('index',DemoListener::class);
//        app('events')->listen('index',function($request){
//            var_dump('callbak');
//            var_dump($request);
//        });


        $request = ['aa'=>'bb'];
        $ret  = app('micro-client')
            ->micro('UserService')
            ->service('App\\Service\\OneService')
            ->pass($request)
            ->run();
//
        return $ret;


////
//        $url = "http://127.0.0.1:8500/v1/health/service/UserService";
//        $client = new Client();
//        $resp = $client->get($url);
//        return json_decode($resp->getBody()->getContents(),true);
    }

    public function swooleDemo()
    {
        $data = Server::stats();
        return $data;
    }

    /**
     * 这里是标题注释
     *
     * @desc 描述
     *
     * @param type $arg1 参数一的说明
     * @param mixed $arg2 参数二的说明
     * @param mixed $mixed 这是一个混合类型
     * @return string a xxxx string(5)
     * @return number b xxxx number(3)
     * @return string c xxxx cn_name()
     * @return float d  xxx float(4,2)
     * @return boolean e xxxx bool()
     * @return float f xxxx price(3,2)
     * @return string g xxxx date(Y-m-d)
     */
    public function swooleTable()
    {
        //内存表
        $tables = SwooleTable::get('table');
        $tables->set('user_id_123',['data'=>json_encode(['a'=>'b','c'=>'d'])]);
        $ret = $tables->get('user_id_123');
        return json_decode($ret['data'],true);
    }

    public function broadcast()
    {
        $param = [
            'a'=>'b','c'=>'d'
        ];
        return Base::service()->pass($param)->broadcast('democast')->run();
    }

    /**
     * @desc 测试
     */
    public function baseTest(Request $request)
    {
        //逆地理编码
//        $this->V3Geocode();
        //获取距离
//        $this->distance();
        //key 有效期
//        return $this->testExpireKey();
        //redis 有序集合
//        return $this->redisSorted();
        //获取用户信息
//        return $this->getDriverInfo();
        //检查socket fd
//        return $this->checkSocketFd();
        //测试获取http head
//        return $this->getHttpHead();
        //查询附近车辆
        return $this->getNearbyDriver($request);
        $key = Base::code('gould_config.key');
        //服务ID
        $sid = Base::code('gould_config.sid');

        $redis = getConnections([
            'name' => 'default_4',
            'database' => 4
        ]);
        dd($redis->del(env('QUEUE_NAME').'_order_realtime_temp_point:*'));
        $ret = $redis->keys(env('QUEUE_NAME').'_order_realtime_positio:*');

        foreach ($ret as $key => $val){
            $data = $redis->ZRANGE($val,0,-1,TRUE);
            $arr = explode(':',$val);
            dd($arr[1]);
        }




        $data = $redis->ZRANGE(env('QUEUE_NAME')."_realtime_position:1281288368293974272",0,-1,TRUE);

        $ret = Base::service(TsApiService::class)
            ->with('key',$key['property2'])
            ->with('sid',$sid['property2'])
            ->with('driver_id','1281288368293974272')
            ->with('order_id','1296439943924599040')
            ->with('trid','380')
            ->with('starttime',date('Y-m-d H:i:s',1571022488))
            ->with('endtime',date('Y-m-d H:i:s',1571045839))
            ->run('pointUpload');
//        $ret = Base::service(TsApiService::class)
//            ->with('key',$key['property2'])
//            ->with('sid',$sid['property2'])
//            ->with('driver_id','1281288368293974272')
//            ->with('order_id','1296439943924599040')
//            ->with('trid','380')
//            ->with('starttime',date('Y-m-d H:i:s',1571022488))
//            ->with('endtime',date('Y-m-d H:i:s',1571045839))
//            ->run('getTerminalTrsearch');
        dd($ret);
        $ret = $redis->exists(env('QUEUE_NAME')."_driver_no_trip:1281288368293974272");

        $data = $redis->hgetall(env('QUEUE_NAME')."_driver_no_trip:".'1281288368293974272');
        dd($data);

        $redis = app('redis')->connection('default');
        $code = Base::code('redis_db.driver_related');
        dd($redis);
        Base::service()
            ->job('testJob')
            ->run();
    }

    /**
     * @desc 逆地理编码
     */
    public function V3Geocode(){
        //获取高德应运key
        $key = Base::code('gould_config.key');
        //Auth
        AMap::auth($key['property2']);
        //Request

        $amap = RestAPI::geo()->recode();

        $amap->setLocation('120.415998,36.305543');

        $response = $amap->request();

        //如果请求高德API失败,根据redis->geo所计算的距离进行派单,(需优化)
        if($response->body['status'] == 0){
            return [];
        }

        //distance 距离 (米) duration 预计行驶时间 秒
        $result = $response->body['regeocode'];

        return $result['formatted_address'];
    }

    /**
     * @desc 获取距离
     */
    public function distance(){
        //获取高德应运key
        $key = Base::code('gould_config.key');
        //Auth
        AMap::auth($key['property2']);
        //Request
        $amap = RestAPI::direction()->distance();

        $amap->setOrigins('120.441883,36.316539');
        $amap->setDestination('120.382048,36.26547');
        $amap->setOutput('json');
        $response = $amap->request();

        //如果请求高德API失败,根据redis->geo所计算的距离进行派单,(需优化)
        if($response->body['status'] == 0){
            return [];
        }
        //distance 距离 (米) duration 预计行驶时间 秒
        $result = $response->body['results'];
        return $result;
    }
    //redis key 有效期
    public function testExpireKey(){

        $code = Base::code('redis_db.driver_refuse');
        $redis = getConnections([
            'name' => 'default_'.$code['property2'],
            'database' => $code['property2']
        ]);
        $driver_id = '1281288368293974272';
        $passenger_id = '1142889689825594113';
        $key = env('QUEUE_NAME').'_driver_refuse_passenger:'.$driver_id.'_'.$passenger_id;
//        return $redis->set($key,'flag');
        return $redis->expire($key,5);
    }

    /**
     * @desc redis 有序集合
     */
    public function redisSorted(){

        $code = Base::code('redis_db.realtime_position');
        $redis = getConnections([
            'name' => 'default_'.$code['property2'],
            'database' => $code['property2']
        ]);
        $key = env('QUEUE_NAME').'_order_realtime_position:1305188843581920512';
//        return $redis->del($key);
        $data = $redis->ZRANGE($key,0,-1,TRUE);

        foreach ($data as $k => $val){
//            dd($redis->zscore($key,$k));
            $orderData = $redis->hgetall(env('QUEUE_NAME')."_trip_order:".$k);

            //执行调度
            Base::service()
                ->with('passenger_id',$orderData['passenger_id'])
                ->with('dep_longitude',$orderData['dep_longitude'])
                ->with('dep_latitude',$orderData['dep_latitude'])
                ->with('order_id',$orderData['order_id'])
                ->job('OrderDispatch')
                ->run();
        }
    }

    public function getDriverInfo(){
//        return substr('13000000001',-4);
//        $data = app('micro-client')
//            ->micro('DriverInfoService')
//            ->service('Micro\\Driver\\Service\\DriverInfoService')
//            ->with('driver_id','1281288368293974272')
//            ->run('getDriverInfo');
//        $driver = $data['data'];

        $data = app('micro-client')
            ->micro('DriverInfoService')
            ->service('Micro\\Driver\\Service\\VehicleInfoService')
            ->with('driver_id','1281288368293974272')
            ->run('getBaseVehiclenInfo');

        return $data['data'];
    }

    public function checkSocketFd()
    {
        $data = [
            'code' => '0000',
            'message' => '连接成功',
            'data' => [
                'type' => '0010'
//                'params' => [1]
            ],
        ];
        $json = json_encode($data,JSON_UNESCAPED_UNICODE);
        $encode =  mb_detect_encoding($json, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
        $str_encode = mb_convert_encoding($json, 'UTF-8', $encode);
        dd($json);

        $code = Base::code('redis_db.fd_link');

        $redis = getConnections([
            'name' => 'default_' . $code['property2'],
            'database' => $code['property2']
        ]);

        return $redis->hgetall(env('QUEUE_NAME') . "_fd_link:4");
    }
    public function getHttpHead(){
        $curl = curl_init();
        $url='http://www.111cn.net';
        curl_setopt($curl, CURLOPT_URL, $url); //设置URL
        curl_setopt($curl, CURLOPT_HEADER, 1); //获取Header
        curl_setopt($curl,CURLOPT_NOBODY,true); //Body就不要了吧，我们只是需要Head
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //数据存到成字符串吧，别给我直接输出到屏幕了
        $data = curl_exec($curl); //开始执行啦～
        $ret = curl_getinfo($curl,CURLINFO_HTTP_CODE); //我知道HTTPSTAT码哦～
        curl_close($curl); //用完记得关掉他
        dd($ret);
        $ret = GHttp::get('https://www.baidu.com/s',[
            'wd' => 'QueryList'
        ],[
            'headers' => [
                'referer' => 'https://baidu.com',
                'User-Agent' => 'Mozilla/5.0 (Windows NTChrome/58.0.3029.110 Safari/537.36',
            ]
        ]);
        dd($ret);
    }
    //查询附近的司机
    public function getNearbyDriver($request){
//        $key = Base::code('gould_config.key');
//        //服务ID
//        $sid = Base::code('gould_config.sid');
//        return Base::service(TsApiService::class)
//            ->with('key',$key['property2'])
//            ->with('sid',$sid['property2'])
//            ->with('driver_id','1446984974567896113')
//            ->run('terminalAdd');



        $request = $request->all();
        $code = Base::code('redis_db.driver_related');

        $redis = getConnections([
            'name' => 'default_'.$code['property2'],
            'database' => $code['property2']
        ]);

        $driver_position = $redis->georadius(env('QUEUE_NAME')."_driver_position:",$request['dep_longitude'],$request['dep_latitude'],5,'km',['WITHDIST','WITHCOORD','ASC']);
        return $driver_position;
    }
    /**
     * @desc getToken
     * @param Request $request
     * @param Token $token
     * @return string
     */
    public function getToken(Request $request,Token $token){
        return $token->setId($request->input('id'))
            ->setName('')
            ->setRole('')
            ->getToken();
    }

}