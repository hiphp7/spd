<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14
 * Time: 14:40
 */
return [

    // 绑定分享
    'appId' => 'wx6ff4a08101358954',
    // 公众帐号
    'AppSecret' => '68c56330376b219506bcc55d467e18ac',

    // 默认ID
    'DEFAULT' => array(
        // 青岛店
        'storeId' => '1483110006864692104',
    ),
    //开放平台 微信支付使用 (APP 登陆/分享所用)
    'WE_CHAT' => array(
        // 绑定支付的APPID
        'appId' => 'wx097e9cd28d848e1f ',
        //
        'AppSecret' => '8b6c646f077583a388185de36be219bd',
        // 商户号
        'mchId' => '1536833271',
        // 商户支付密钥
        'key' => '8b6c646f077583a388185de36be219bd',
        // 升级通知回调地址
//        'notifyUrl' => 'http://www.apibase.com/weChatCallback',
        'notifyUrl' => 'http://apincar.liugeche.cn/weChatCallback',
        'planPayNotifyUrl' => 'http://lgapi.rundle.cn/planPayCallback',
        // 交易方式
        'tradeType' => 'NATIVE',
        'body' => '用户升级',
    ),
    //公众号appid  获取微信用户信息 (WEB 登陆/分享所用)
    'SHARE' => array(
        // 绑定分享
        'appId' => 'wxa2dd626459d17d33',
        //
        'AppSecret' => 'f019316d412b2b9c0617966c73d038d2',
        // 商户号
        'mchId' => '1528500401',
        // 商户支付密钥
        'key' => 'da0704c960e04219a12209c2aa092dc4',
        // 交易方式
        'tradeType' => 'JSAPI',
        // 转售回调地址
//        'notifyUrl' => 'http://www.apibase.com/weChatCallback',
        'notifyUrl' => 'http://api.pygj888.com/weChatCallback',

        // 获取open_id
        'OpenIdUrl' => 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.'wxbee7df8b0631d2d0'.'&secret='.'fd3174be35dad04833d08bafad5fe034'.'&grant_type=authorization_code&code=',
        // 获取Token url
        'tokenUrl' => 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential',
        // 获取Ticket url
        'ticketUrl' => 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=',
        // Token缓存路径
        'root' => base_path().'/public'.'/checkToke.php',
    ),
    // 短信
    'SMS_INFO' => array(
        'url' => 'http://124.172.234.157:8180/Service.asmx/SendMessageStr',
         'Id' =>'1349',
         'Name' =>'luketongbao',
         'Psw' => '123456'
//        'Id' => '1349',
//        'Name' => 'liugeche',
//        'Psw' => '992151'
    ),
    // 百度定位
    'BD' => array(
        'ak' => 'qVGHg1K9H3wSDjsXk7OGYKWnYD46X8l8',
        'coordinate' => 'http://api.map.baidu.com/geocoder/v2/?address=%s&output=%s&ak=%s',
        'position' => 'http://api.map.baidu.com/geocoder/v2/?location=%s&coordtype=%s&output=%s&ak=%s',
        'distance' => 'http://api.map.baidu.com/routematrix/v2/driving?origins=%s&destinations=%s&coord_type=%s&$tactics=%s&ak=%s',
    ),
    // 邮件参数
    'MAIL' => array(
        'host' => 'smtp.exmail.qq.com',
        'username' => 'liugq@nxp.cn',
        'password' => 'WeRCq7Ag5ahRyjgB',
        'port' => '465',
        'address' => array(
            '0' => 'liugq@nxp.cn',
        ),
    ),

];


//const KEY = '4F0E0971BECD13671CEF363C42352323';
//const JSAPI = '5DE312DD35B24DD772EEE8EFAD1028EF';