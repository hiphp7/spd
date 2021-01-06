<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/11/28
 * Time: 10:14
 */

namespace Micro\User\Repo;


use Micro\Common\Contract\Repository;
use Micro\User\Model\CommBankDb;

class CommBankDbRepo extends Repository
{
    public function __construct(CommBankDb $model)
    {
        $this->model = $model;
    }

    public function getAreaBankList($request)
    {
        $request['stateName'] = preg_replace('# #','',$request['stateName']);
        $headEng = strtolower($request['headEng']);
        $sql = $this->model
            ->select('id','code','name','head_eng','head_name','state_name','city_name','state_code','city_code')
            ->where('head_eng',$headEng)
            ->where('state_name','like','%'.$request['stateName'].'%');

        if(isset($request['cityName'])){
            $request['cityName'] = preg_replace('# #','',$request['cityName']);
            $sql = $sql->where('city_name','like','%'.$request['cityName'].'%');
        }

        $ret = optional(
            $sql->paginate($request['pageSize']))
            ->toArray();

        return $ret['data'];
    }
}