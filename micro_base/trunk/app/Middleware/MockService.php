<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/8/6
 * Time: 11:56
 */

namespace App\Middleware;


use Closure;
use Illuminate\Container\BoundMethod;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Micro\Common\Tool\Anno;
use Micro\Common\Tool\Mock;
use Unirest\Exception;

class MockService
{

    public function handle(Request $request, Closure $next)
    {
        $mock_service = $request->get('mock_service',false);
        if($mock_service){
            $path = $request->getPathInfo();
            $method = $request->method();
            $key = $method.$path;
            $routers = app()->router->getRoutes();
            $currentRoute = $routers[$key]??[];
            if($currentRoute){

                return $this->callMockMethod($currentRoute,$request);
//                return $this->getMockResponse($currentRoute,$request);
            }
        }
        return $next($request);
    }


    public function callMockMethod($currentRoute,$request)
    {
        list($controller, $method) = explode('@', $currentRoute['action']['uses']);
        $action = $controller.'@'.$method."MockReturn";

        $data = BoundMethod::call(app(),$action,[$request]);

        $resp = new Response();
        $resp->setContent($data);
        return $resp;
    }



    public function getMockResponse($currentRoute,$request)
    {
        list($controller, $method) = explode('@', $currentRoute['action']['uses']);
        $anno = app(Anno::class)->reflection($controller,$method);
        $data=[];
        $mock = app(Mock::class);
        foreach ($anno->getReturn() as $key=>$return){
            $data[$key] = $mock->run($return['rule']);
        }
        $resp = new Response();
        $resp->setContent($data);
        return $resp;

    }

}