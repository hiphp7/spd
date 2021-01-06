<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/10/15
 * Time: 14:39
 */

return array(
    //短信
    'PARNER_SMS_TEMPLATE' => array('code' => 'S0801', 'type' => 'PARNER'),
    'AGENT_SMS_TEMPLATE' => array('code' => 'S0802', 'type' => 'AGENT'),
    'MERC_SMS_TEMPLATE' => array('code' => 'S0803', 'type' => 'MERC'),
    'USER_SMS_TEMPLATE' => array('code' => 'S0804', 'type' => 'USER'),
    'RESERVE_SMS_TEMPLATE' => array('code' => 'S0805', 'type' => 'RESERVE'),
    'BUY_CONFIRM_SUC' => array('code' => 'S0810', 'type' => 'USER'),

    //消息
    'USER_REGISTER_SUC' => array('code' => 'U0100', 'type' => '00', 'msg' => '注册成功', 'path' => 'news/page/news_details'),
    'ORDER_INTER_CITY'     => array('code' => 'G0100', 'type' => '00', 'msg' => '城际订单','path' => 'news/page/news_details'),
    'USER_REWARD_SUC'   => array('code' => 'U0102', 'type' => '00', 'msg' => '奖励金到账', 'path' => 'news/page/news_details'),
    'USER_RESERVE_SUC'  => array('code' => 'J0102', 'type' => '00', 'msg' => '客户预约', 'path' => 'news/page/news_details'),
    //Ligu消息推送
    //消息
    'USER_MESS_CALL'        => array('code' => 'L0100', 'type' => '00', 'msg' => '留言提醒', 'path' => 'news/page/news_details'),
    'USER_GIVEN_CALL'       => array('code' => 'L0101', 'type' => '00', 'msg' => '物品获赠提醒','path' => 'news/page/news_details'),
    'USER_PLAN_UNFINNISH'   => array('code' => 'L0102', 'type' => '00', 'msg' => '未完成计划提醒', 'path' => 'news/page/news_details'),
    'USER_MEMBER_DUE'       => array('code' => 'L0103', 'type' => '00', 'msg' => '会员到期提醒', 'path' => 'news/page/news_details'),

    //消息类型
    '01' => array(),
    '02' => array('code' => 'notice', 'type' => '公告消息', 'img' => R('webimg/msg/gonggao.png')),
    '03' => array('code' => 'task', 'type' => '新闻', 'img' => R('webimg/msg/news.png')),
    '04' => array('code' => 'profit', 'type' => '分润账单消息', 'img' => R('webimg/msg/liushui.png')),
    '05' => array('code' => 'userup', 'type' => '会员升级', 'img' => R('webimg/msg/hysj.png')),
    '06' => array('code' => 'carstyle', 'type' => '车主风采', 'img' => R('webimg/msg/czfc.png')),
    '07' => array('code' => 'carsafe', 'type' => '车辆保险', 'img' => R('webimg/msg/cbx.png')),
    '08' => array('code' => 'system', 'type' => '系统消息', 'img' => R('webimg/msg/xt.png')),


//        消息类型
    'msg_type' => [
        //订单消息
        'order' => [ //0020-0029 订单类型消息,推送司机
            'realtime' => [
                'code' => 'G0020', 'type' => '0020', 'msg' => '实时订单', 'path' => 'news/page/news_details','img' => R('webimg/msg/gonggao.png')
            ],
            'advance' => [
                'code' => 'G0021', 'type' => '0021', 'msg' => '预约订单', 'path' => 'news/page/news_details','img' => R('webimg/msg/gonggao.png')
            ],
            'inter_city_order' => [
                'code' => 'G0022', 'type' => '0022', 'msg' => '城际订单', 'path' => 'news/page/news_details','img' => R('webimg/msg/gonggao.png')
            ],
        ],
        'dispatch' => [ //0030-0039 订单
            'fail' =>
                [
                    'code' => 'G0030', 'type' => '0030', 'msg' => '附近车辆较少,正在努力派单中', 'path' => 'news/page/news_details','img' => R('webimg/msg/gonggao.png')
                ],
            'success' =>
                [
                    'code' => 'G0031', 'type' => '0031', 'msg' => '司机接单成功,推送给乘客', 'path' => 'news/page/news_details','img' => R('webimg/msg/gonggao.png')
                ],
            'driver_cancel' =>
                [
                    'code' => 'G0032', 'type' => '0032', 'msg' => '司机取消订单,推送给乘客', 'path' => 'news/page/news_details','img' => R('webimg/msg/gonggao.png')
                ],
            'passenger_cancel' =>
                [
                    'code' => 'G0033', 'type' => '0033', 'msg' => '乘客取消订单,推送给司机', 'path' => 'news/page/news_details','img' => R('webimg/msg/gonggao.png')
                ],
            'driver_arrive' =>
                [
                    'code' => 'G0034', 'type' => '0034', 'msg' => '司机到达乘客上车点,推送给乘客', 'path' => 'news/page/news_details','img' => R('webimg/msg/gonggao.png')
                ],
            'trip_start' =>
                [
                    'code' => 'G0035', 'type' => '0035', 'msg' => '司机接到乘客,行程开始', 'path' => 'news/page/news_details','img' => R('webimg/msg/gonggao.png')
                ],
            'trip_end' =>
                [
                    'code' => 'G0036', 'type' => '0036', 'msg' => '送达乘客目的地,订单结束', 'path' => 'news/page/news_details','img' => R('webimg/msg/gonggao.png')
                ],
            'cheques' =>
                [
                    'code' => 'G0037', 'type' => '0037', 'msg' => '订单结束,发起收款', 'path' => 'news/page/news_details','img' => R('webimg/msg/gonggao.png')
                ],
            'subscribe_suc' =>
                [
                    'code' => 'G0038', 'type' => '0038', 'msg' => '城际顺风车,乘客预约成功,通知司机', 'path' => 'news/page/news_details','img' => R('webimg/msg/gonggao.png')
                ],
            'cancel_freeride_order' => [
                'code' => 'G0039', 'type' => '0039', 'msg' => '城际顺风车,司机取消订单,通知乘客', 'path' => 'news/page/news_details','img' => R('webimg/msg/gonggao.png')
            ],
            'success_freeride_order' => [
                'code' => 'G0040', 'type' => '0040', 'msg' => '城际顺风车,乘客预约成功,通知司机', 'path' => 'news/page/news_details','img' => R('webimg/msg/gonggao.png')
            ],
            'success_freeride_driver' => [
                'code' => 'G0041', 'type' => '0041', 'msg' => '顺风车,司机确认接单,通知乘客', 'path' => 'news/page/news_details','img' => R('webimg/msg/gonggao.png')
            ],

        ]
     ],

    //消息标题
    'MSGTITLE' => '麦田商旅 | 注册成功',

    //短信验证码长度
    'CODELEN' => 4,

    //同一手机号一天内短信发送次数
    'SMS_NUMBER' => 20,

);