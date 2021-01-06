<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/6/28
 * Time: 10:33
 */

//$router->get('/', 'IndexController@index');
$router->get('/user/rpcDemo', 'IndexController@rpcDemo');
$router->get('/user/baseTest', 'IndexController@baseTest');
$router->get('/user/getToken', 'IndexController@getToken');
//$router->post('/user/post', 'IndexController@swooleTable');
