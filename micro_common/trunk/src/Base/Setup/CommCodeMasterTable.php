<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/1/9
 * Time: 13:43
 */

namespace Micro\Common\Base\Setup;


use Micro\Common\Contract\Middleware;
use Micro\Common\Criteria\Criteria;
use Micro\Common\Base\Repository\CodeMasterRepo;
use Closure;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class CommCodeMasterTable extends Middleware
{

    public $repo;
    public function __construct(CodeMasterRepo $repo)
    {
        $this->repo = $repo;
    }

    public function handle($request, Closure $next)
    {
        try{
            $this->createtable();
            $this->insertData();
            echo 'comm_code_master 初始化完毕'.PHP_EOL;
            return $next($request);
        }catch (\Exception $e){
            Log::info('------ '.$e->getTrace());

            Err('comm_code_master 初始化错误');
        }
    }


    public function createtable()
    {

        Schema::dropIfExists('comm_code_master');
        Schema::create('comm_code_master', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->primary('id');
            $table->string('id',21)->comment('ID');
            $table->string('code',30);
            $table->string('code_key',30);
            $table->string('property1',100)
                ->nullable()->comment('标题-属性1');
            $table->string('property2',100)
                ->nullable()->comment('属性2');
            $table->string('property3',100)
                ->nullable()->comment('属性3');
            $table->string('property4',100)
                ->nullable()->comment('属性4');
            $table->string('property5',100)
                ->nullable()->comment('属性5');
            $table->string('property6',100)
                ->nullable()->comment('属性6');
            $table->string('property7',100)
                ->nullable()->comment('属性7');
            $table->string('property8',100)
                ->nullable()->comment('属性8');
            $table->string('property9',100)
                ->nullable()->comment('属性9');
            $table->string('property10',100)
                ->nullable()->comment('属性10');
            $table->string('level_name',10)
                ->nullable()->comment('邀请等级码');
            $table->dateTime('create_time');
            $table->dateTime('update_time');

        });
        
    }

    public function insertData()
    {
        $ret = $this->getData();
        $data = [];
        foreach($ret as $k=>$v){
            $data = array_merge($data,$v);
        }
        $this->repo->insert($data);

    }

    public function getData()
    {
        $ret[] = $this->profitK0650();
        $ret[] = $this->profitK0690();
        $ret[] = $this->freezeChange();



        $ret[] = $this->transCode();
        $ret[] = $this->transRisk();
        $ret[] = $this->userRisk();

        $ret[] = $this->transFinance();



        $ret[] = $this->accountType();
        $ret[] = $this->accountObject();

        $ret[] = $this->financeCategory();


        return $ret;
    }

    public function profitK0650()
    {
        return [
            [
                'id'=>ID(),
                'code' =>'profitK0650',
                'code_key' =>'DirectProfitAmount',
                'property1' =>'直推分润比例',
                'property2' =>'0.04',
                'property3' =>'0.01',
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s"),
            ],
            [
                'id'=>ID(),
                'code' =>'profitK0650',
                'code_key' =>'IndirectProfitAmount',
                'property1' =>'间接分润比例',
                'property2' =>'0.016',
                'property3' =>'0.004',
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ],
            [
                'id'=>ID(),
                'code' =>'profitK0650',
                'code_key' =>'P1601ProfitAmount',
                'property1' =>'发起人分润',
                'property2' =>'0.016',
                'property3' =>'0.004',
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ]

        ];
    }

    public function profitK0690()
    {
        return [
            [
                'id'=>ID(),
                'code' =>'profitK0690',
                'code_key' =>'DirectProfitAmount',
                'property1' =>'直推分润比例',
                'property2' =>'0.04',
                'property3' =>'0.01',
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ],
            [
                'id'=>ID(),
                'code' =>'profitK0690',
                'code_key' =>'IndirectProfitAmount',
                'property1' =>'间接分润比例',
                'property2' =>'0.016',
                'property3' =>'0.004',
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ],
            [
                'id'=>ID(),
                'code' =>'profitK0690',
                'code_key' =>'P1601ProfitAmount',
                'property1' =>'发起人分润',
                'property2' =>'0.016',
                'property3' =>'0.004',
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ]

        ];
    }

    public function freezeChange()
    {
        return [
            [
                'id'=>ID(),
                'code' =>'freeze_change',
                'code_key' =>'1000',
                'property1' =>'根据交易解冻比例',
                'property2' =>'0.2',
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ],
            [
                'id'=>ID(),
                'code' =>'freeze_change',
                'code_key' =>'2000',
                'property1' =>'根据交易解冻比例',
                'property2' =>'0.4',
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ]
        ];
    }


    public function transCode()
    {
        return [
            [
                'id'=>ID(),
                'code' =>'trans_code',
                'code_key' =>'withdraw',
                'property1' =>'提现交易码',
                'property2' =>'A0700',
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ]
        ];
    }



    public function transRisk()
    {
        return [
            [
                'id'=>ID(),
                'code' =>'A0700_risk',
                'code_key' =>'P1101',
                'property1' =>'2:单笔最小,3:单笔最大,4:日限额,5:日限次',
                'property2' =>'100',
                'property3' =>'20000',
                'property4' =>'20000',
                'property5' =>'2',
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ]
        ];
    }

    public function accountType()
    {
        return [
            [
                'id'=>ID(),
                'code' =>'account_type',
                'code_key' =>'ASSET',
                'property1' =>'账户类型-资产',
                'property2' =>'10',
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ],
            [
                'id'=>ID(),
                'code' =>'account_type',
                'code_key' =>'CREDIT',
                'property1' =>'账户类型-信用',
                'property2' =>'20',
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ],
            [
                'id'=>ID(),
                'code' =>'account_type',
                'code_key' =>'FREEZE',
                'property1' =>'账户类型冻结',
                'property2' =>'30',
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ],
            [
                'id'=>ID(),
                'code' =>'account_type',
                'code_key' =>'LEND',
                'property1' =>'账户类型垫资',
                'property2' =>'40',
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ],
            [
                'id'=>ID(),
                'code' =>'account_type',
                'code_key' =>'POINTS',
                'property1' =>'账户类型积分',
                'property2' =>'50',
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ],
            [
                'id'=>ID(),
                'code' =>'account_type',
                'code_key' =>'REWARD',
                'property1' =>'账户类型佣金',
                'property2' =>'60',
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ],
            [
                'id'=>ID(),
                'code' =>'account_type',
                'code_key' =>'COINS',
                'property1' =>'账户类型金币',
                'property2' =>'70',
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ]
        ];
    }


    public function accountObject()
    {
        return [
            [
                'id'=>ID(),
                'code' =>'account_object',
                'code_key' =>'MERC',
                'property1' =>'账户对象-商户账户',
                'property2' =>'10',
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ],
            [
                'id'=>ID(),
                'code' =>'account_object',
                'code_key' =>'AGENT',
                'property1' =>'账户对象-代理账户',
                'property2' =>'20',
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ],
            [
                'id'=>ID(),
                'code' =>'account_object',
                'code_key' =>'CHANNEL',
                'property1' =>'账户对象-通道账户',
                'property2' =>'30',
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ],
            [
                'id'=>ID(),
                'code' =>'account_object',
                'code_key' =>'PRIVATE',
                'property1' =>'账户对象-企业自有',
                'property2' =>'40',
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ],
            [
                'id'=>ID(),
                'code' =>'account_object',
                'code_key' =>'BANK',
                'property1' =>'外界银行',
                'property2' =>'50',
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ],
            [
                'id'=>ID(),
                'code' =>'account_object',
                'code_key' =>'PAYFOR',
                'property1' =>'代付账户',
                'property2' =>'60',
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ],
            [
                'id'=>ID(),
                'code' =>'account_object',
                'code_key' =>'USER',
                'property1' =>'账户对象-用户',
                'property2' =>'80',
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ]
        ];
    }

    public function financeCategory()
    {
        return [
            [
                'id'=>ID(),
                'code' =>'finance_category',
                'code_key' =>'ASSET',
                'property1' =>'财务科目-资产',
                'property2' =>'1',
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ],
            [
                'id'=>ID(),
                'code' =>'finance_category',
                'code_key' =>'LIABILITIES',
                'property1' =>'财务科目-负债',
                'property2' =>'2',
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ],
            [
                'id'=>ID(),
                'code' =>'finance_category',
                'code_key' =>'OWNERS',
                'property1' =>'财务科目-所有者权益',
                'property2' =>'3',
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ],
            [
                'id'=>ID(),
                'code' =>'finance_category',
                'code_key' =>'REVENUE',
                'property1' =>'财务科目-收入',
                'property2' =>'4',
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ],
            [
                'id'=>ID(),
                'code' =>'finance_category',
                'code_key' =>'EXPENSES',
                'property1' =>'财务科目-费用',
                'property2' =>'5',
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ],
            [
                'id'=>ID(),
                'code' =>'finance_category',
                'code_key' =>'INCOME',
                'property1' =>'财务科目-利润',
                'property2' =>'6',
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ]

        ];
    }

    public function userRisk()
    {

        return [
            [
                'id'=>ID(),
                'code' =>'user_status_risk',
                'code_key' =>'A0700',
                'property1' =>'A0700状态限制',
                'property2' =>"10,20,30",
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ],
            [
                'id'=>ID(),
                'code' =>'user_Level_risk',
                'code_key' =>'A0700',
                'property1' =>'A0700等级限制',
                'property2' =>"P1001",
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ]
        ];

    }

    public function transFinance()
    {
        return [
            [
                'id'=>ID(),
                'code' =>'trans_finance',
                'code_key' =>'A0700',
                'property1' =>'交易记账码',
                'property2' =>"K0700",
                'property3' => null,
                'property4' => null,
                'property5' => null,
                'create_time' =>date("Y-m-d H:i:s"),
                'update_time' =>date("Y-m-d H:i:s")
            ]
        ];
    }
    
}