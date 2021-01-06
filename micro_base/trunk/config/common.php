<?php
return array(
    'share'   => array('src'=>R('webimg/slide/share.png')),
    'upgrade' => array('src'=>R('webimg/slide/upgrade.png')),

    //明细流水账单  图标    新增交易时需要增加
    'trans_order_status_img' =>array(
        'A0120' => R('webimg/icon/upgrade.png'),
        'A0200' => R('webimg/icon/to_pay.png'),
        'A0350' => R('webimg/icon/put.png'),
        'A0360' => R('webimg/icon/reduce.png'),
        //
        'A0650' => R('webimg/icon/upgrade.png'),
        'A0651' => R('webimg/icon/to_pay.png'),
        'A0652' => R('webimg/icon/put.png'),
        'A0690' => R('webimg/icon/reduce.png'),
        'A0700' => R('webimg/icon/put.png'),

    ),
    'cafe_poster' => R('webimg/param/2.png'),
    'assets_icon' => [
        '10' => ['icon'=> R('webimg/icon/balance_icon.png')],
        '20' => ['icon'=> R('webimg/icon/balance_icon.png')],
        '30' => ['icon'=> R('webimg/icon/freeze_icon.png')],
        '40' => ['icon'=> R('webimg/icon/bean_icon.png')],
        '50' => ['icon'=> R('webimg/icon/RD.png')],
        '60' => ['icon'=> R('webimg/icon/redPacket_icon.png')],
        '70' => ['icon'=> R('webimg/icon/gold_icon.png')],
    ],
    'goods_order' => [
        '0000' => '订单完成',
        '0010' => '待支付',
        '0020' => '支付成功',
        '0021' => '付款失败',
        '0022' => '取消订单',
        '0023' => '删除订单',
        '0030' => '寄售中',
        '0031' => '展示中',
        '0040' => '待发货',
        '0050' => '待收货'
    ],
    'invite_code' => [
        '0010' => '未使用',
        '0020' => '已使用'
    ],
    'goods_class' => [
        '0010' => '打赏',
        '0020' => '鼓励',
        '0030' => '起哄'
    ],
    'general_code' => 'http://lgpms.rundle.cn/appstore/wxshare/index.html#/pages/wxRegister/index?user_id=',
);