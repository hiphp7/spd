<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/12/4
 * Time: 13:44
 */

namespace Micro\User\Repo;


use Micro\Common\Contract\Repository;
use Micro\User\Model\CommCodeMaster;

class CommCodeMasterRepo extends Repository
{
    public function __construct(CommCodeMaster $model)
    {
        $this->model = $model;
    }

    // 获取数据字典信息
    public function getConfigure($code,$key)
    {
        return optional($this->model
            ->select('property1','property2','property3','property4','property5'
            //    ,'property6','property7','property8','property9','property10'
            )
            ->where('code',$code)
            ->where('code_key',$key)
            ->first())
            ->toArray();
    }

    public function getConfigureByKey($code_key){
        return optional($this->model
            ->select('property1','property2','property3','property4','property5'
            )
            ->where('code_key',$code_key)
            ->first())
            ->toArray();
    }

    public function getConfigureById($id){
        return optional($this->model
            ->select('id','property1','property2','property3','property4','property5'
            )
            ->where('id',$id)
            ->first())
            ->toArray();
    }

    // 获取数据字典信息
    public function getCodeConfigure($code)
    {
        return optional($this->model
            ->select(
                'code','code_key',
                'property1','property2','property3','property4','property5'
            )
            ->where('code',$code)
            ->get())
            ->toArray();
    }

    //获取数据字典信息
    public function getCode($key)
    {
        $query = $this->model->select(
            'code','code_key',
            'property1','property2','property3','property4','property5',
            'property6','property7','property8','property9','property10'
        );

        $explode = explode(".",$key);
        $count = count($explode);
        if($count == 0){
            return [];
        }
        if($count == 1){
            $query->where('code',$explode[0]);
            $ret = $query->get();
            return Arr($ret);
        }
        if($count ==2){
            $query->where('code',$explode[0]);
            $query->where('code_key',$explode[1]);
            $ret = $query->first();
            return Arr($ret);
        }
        if($count >= 3){
            $query->where('code',$explode[0]);
            $query->where('code_key',$explode[1]);
            $ret = Arr($query->first());
            return $ret[$explode[2]]??'';
        }
    }
}