<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/1/14
 * Time: 09:45
 */

namespace Micro\Common\Base\Repository;


use Micro\Common\Contract\Repository;
use Micro\Common\Criteria\Criteria;
use Micro\Common\Base\Model\UserCodeMasterModel;

class UserCodeMasterRepo extends Repository
{

    public $model;

    public function __construct(UserCodeMasterModel $model)
    {
        $this->model = $model;
    }



    public function getCode($user_id,$key)
    {
        $criteria[] = Criteria::create()->select([
            'code','code_key',
            'property1','property2','property3','property4','property5',
            'property6','property7','property8','property9','property10'
        ])->where('user_id',$user_id);

        $explode = explode(".",$key);
        $count = count($explode);
        if($count == 0 ) {
            return [];
        }
        if($count == 1){
            $criteria[] =  Criteria::create()->where('code',$explode[0]);
            $ret = [];
            $list =   $this->cacheGet(
                $key,
                config('cache.repo.long'),
                $criteria
            );

            foreach($list as $k=>$v){
                $ret[$v['code_key']] = $v;
            }
            return $ret;
        }

        $criteria[] =  Criteria::create()
            ->where('code',$explode[0])
            ->where('code_key',$explode[1]);

        $ret = $this->cacheFirst($key,config('cache.repo.long'),$criteria);

        if($count == 2) return $ret;
        if($count >= 3) return $ret[$explode[2]] ?? null;

    }


}