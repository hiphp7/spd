<?php

/**
 * 财务系统
 *
 * update acct_account_balance
   set acct_account_balance.balance = 0 , acct_account_balance.opening_balance =0, acct_account_balance.occurred_amount =0 ,acct_account_balance.closing_order =null ,acct_account_balance.direction =null
 */
return [
    'sign_key' =>'559EDD35041D3C11F9BBCED912F4DE6A',
    'cashier' =>[//检查参数
        'check'=>[

        ]
    ],
    'policy' => [//策略配置中间件
        'K0600' =>[]
    ],

    'middle_process' => [//记账模板生成 流水 处理      1.

    ],

    'process_bean' =>[//获取具体记账关联 账户 (系统账户\用户编号等)    2.
    ],

    'account_bean' => [//获取套账号
        'System'=>\App\Modules\Finance\Bean\AccountBean\SystemtAccount::class

    ],
    'amount_bean' =>[//计算 获取 借贷  金额******************************************

    ],

    'bookingupdate' =>[//更新账户余额[通过记账流水相关]
        'GetNeedBookOrder' =>Micro\Finance\Middleware\BookingProcess\GetNeedBookOrder::class,
        'CheckBalanceOrder' =>Micro\Finance\Middleware\BookingProcess\CheckBalanceOrder::class,
    ],


    //---------------------------------------------------------------------------------
    'ACCOUNT_BOOK_FAILED'      => array('code' => 'K9999', 'msg' => '财务记账处理失败'),
    'TRANS_ORDER_REPEATE'      => array('code' => 'K9000', 'msg' => '交易流水重复记账'),

    'STATUS_PROCESS'         => ['code' =>'1', 'msg' =>'交易中'],
    'STATUS_SUCCESS'         => ['code' =>'2', 'msg' =>'成功'],
    'STATUS_FAIL'            => ['code' =>'3', 'msg' =>'失败'],
    'STATUS_EXPORT'          => ['code' =>'4', 'msg'=>'报表已导出'],
    'STATUS_APPROVED'        => ['code' =>'5', 'msg'=>'审核中'],
    'STATUS_REQ_ACCT'        => ['code' =>'6', 'msg'=>'重新记账申请'],
    'STATUS_ACCT_OK'         => ['code' =>'7', 'msg'=>'重新记账成功'],
    'STATUS_CHANNEL_FAIL'    => ['code' =>'8', 'msg'=>'通道交易失败'],
    'STATUS_FINISH'          => ['code' =>'9', 'msg'=>'关闭交易'],
    //账户对象
    'ACCOUNT_OBJECT_MERC'      => ['code' => '10', 'msg'    => '商户账户'],
    'ACCOUNT_OBJECT_AGENT'     => ['code' => '20', 'msg'    => '代理账户'],
    'ACCOUNT_OBJECT_CHANNEL'   => ['code' => '30', 'msg'    => '通道账户'],
    'ACCOUNT_OBJECT_PRIVATE'   => ['code' => '40', 'msg'    => '企业自有'],
    'ACCOUNT_OBJECT_PARTNER'   => ['code' => '70', 'msg'    => '城市合伙人'],
    'ACCOUNT_OBJECT_USER'      => ['code' => '80', 'msg'    => '用户账户'],
    //账户类型
    'ACCOUNT_TYPE_ASSET'       => ['code' => '10', 'msg'    => '资产'],
    'ACCOUNT_TYPE_CREDIT'      => ['code' => '20', 'msg'    => '信用'],
    'ACCOUNT_TYPE_FREEZE'      => ['code' => '30', 'msg'    => '冻结'],
    'ACCOUNT_TYPE_LEND'        => ['code' => '40', 'msg'    => '垫资'],
    'ACCOUNT_TYPE_POINTS'      => ['code' => '50', 'msg'    => '积分'],
    'ACCOUNT_TYPE_REWARD'      => ['code' => '60', 'msg'    => '佣金'],
    'ACCOUNT_TYPE_COINS'       => ['code' => '70', 'msg'    => '金币'],


    //总账科目
    'RECEIVABLE'               => ['name' => '1122','msg'   => '应收账款'],//资产类
    'PAYABLE'                  => ['name' => '2202','msg'   => '应付账款'],//负债类
    'MAIN_REVENUE'             => ['name' => '1122','msg'   => '主营业收入'],
    //
    'CATEGORY_ASSET'           => array('code' => '1', 'msg'     => '资产'),
    'CATEGORY_LIABILITIES'     => array('code' => '2', 'msg'     => '负债'),
    'CATEGORY_OWNERS'          => array('code' => '3', 'msg'     => '所有者权益'),
    'CATEGORY_REVENUE'         => array('code' => '4', 'msg'     => '收入'),
    'CATEGORY_EXPENSES'        => array('code' => '5', 'msg'     => '费用'),
    'CATEGORY_INCOME'          => array('code' => '6', 'msg'     => '收益'),

    //借贷判断
    'ACCOUNT_CATEGORY'         =>array(
        '1002'  =>'CATEGORY_ASSET',
        '1122'  =>'CATEGORY_ASSET',
        '2202' =>'CATEGORY_LIABILITIES',
        '6001' =>'CATEGORY_INCOME',
        '6401' =>'CATEGORY_EXPENSES'
    ),


    //记账
    'BOOK_BALANCE_NO_UPDATE'   => ['code' => '0','msg'      => '余额未更新状态'],
    'BOOK_BALANCE_UPDATED'     => ['code' => '1','msg'      => '余额已更新状态'],
    'BOOK_BALANCE_WAITUPDATED' => ['code' => '2','msg'      => '余额更新中'],

    'BOOK_ORDER_MAKE'          => ['code' => '10','msg'     => '制单'],
    'BOOK_ORDER_APPROVE'       => ['code' => '20','msg'     => '审核'],
    'BOOK_ORDER_BOOKING'       => ['code' => '30','msg'     => '记账'],

    //银行存款                     => 1002
    '100200'                   => ['code' => '100200','msg' => '资金池'],
    '100201'                   => ['code' => '100201','msg' => '红包'],//红包账户
    '100202'                   => ['code' => '100202','msg' => '积分账户'],//积分账户
    //资产类  应收账款                     => 1122  贷减  借加
    '112200'                   => ['code' => '112200','msg' => '企业应收'],
    '112201'                   => ['code' => '112201','msg' => '代付通道'],//******** */
    '112280'                   => ['code' => '112280','msg' => '订单明细资产'],
    //负债类  应付账款                     => 2202  贷加  借减
    '220200'                   => ['code' => '220200','msg' => '企业应付'],
    '220203'                   => ['code' => '220203','msg' => '产品成本'],
    '220216'                   => ['code' => '220216','msg' => '乡镇加权'],//4%
    '220217'                   => ['code' => '220217','msg' => '区县加权'],//3%
    '220218'                   => ['code' => '220218','msg' => '市级加权'],//2%
    '220219'                   => ['code' => '220219','msg' => '省级加权'],//1%
    '220201'                   => ['code' => '220201','msg' => '代扣通道'],//******** */
    '220203'                   => ['code' => '220203','msg' => '应付款'],//******** */
    //'220202'                   => ['code' => '220202','msg' => '咖啡豆代收应付款'],//******** */
    '220280'                   => ['code' => '220280','msg' => '呖咕积分'],
    '220290'                   => ['code' => '220290','msg' => '用户提现的到账总额'],//******** */
    '220291'                   => ['code' => '220291','msg' => '提现一共支出的手续费'],//会议企业代收应付款
    '220299'                   => ['code' => '220299','msg' => '外界银行'],
    '220300'                   => ['code' => '220300','msg' => '呖咕金'],
    //主营收入                     => 6001
    '600100'                   => ['code' => '600100','msg' => '主营收入'],
    '600101'                   => array('code' => '600101','msg' => '金币收入'),
    '600102'                   => array('code' => '600102','msg' => '积分收入'),
    '600103'                   => array('code' => '600103','msg' => '系统业绩'),
    '600104'                   => array('code' => '600104','msg' => '打赏账户'),
    //主营成本                     => 6401   借加 贷减
    '640100'                   => ['code' => '640100','msg' => '主营成本'],
    '640101'                   => ['code' => '640101','msg' => '快捷支付的手续费'],//******** */
    '640102'                   => ['code' => '640102','msg' => '支付通道成本'],//******** */
    '640103'                   => ['code' => '640103','msg' => 'POS机收款费率成本'],//******** */
    '640110'                   => ['code' => '640104','msg' => '金币赠送企业成本'],//******** */
    '640105'                   => ['code' => '640105','msg' => '订单产品成本'],//******** */
    '640106'                   => ['code' => '640106','msg' => '未启用成本账户'],//******** */
    '640107'                   => ['code' => '640107','msg' => '积分充值成本'],//******** */
];