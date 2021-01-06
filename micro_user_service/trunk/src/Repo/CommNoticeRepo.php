<?php
/**
 * Created by PhpStorm.
 * User: qu
 * Date: 2019/11/7
 * Time: 14:36
 */

namespace Micro\User\Repo;


use Micro\Common\Contract\Repository;
use Micro\Common\Criteria\Criteria;
use Micro\User\Model\CommNotice;

class CommNoticeRepo extends Repository
{
    public $model;

    public function __construct(CommNotice $model)
    {
        $this->model = $model;
    }

    //获取基本司机信息
    public function getComProblem(){
        $criteria = Criteria::create()
            ->from('comm_notice')
            ->where('valid_status','1')
            ->where('close_status','1')
            ->where('notice_type','50')
            ->orderBy('create_time','asc');

        $problem=$this->cacheGet(
            'Notice_CommenProblem',
            60,
            $criteria,
            ['*']
        );
        $ret=array();
        foreach ($problem as $value){
            $ret1['title']=$value['notice_title'];
            $ret1['content']=$value['notice_content'];
            $ret[]=$ret1;
        }
        return $ret;
    }

    //获取基本司机信息
    public function getQualifications(){
        $criteria = Criteria::create()
            ->from('comm_notice')
            ->where('valid_status','1')
            ->where('close_status','1')
            ->where('notice_type','60')
            ->orderBy('create_time','asc');

        $problem=$this->cacheGet(
            'Notice_Qualifications',
            60,
            $criteria,
            ['*']
        );
        $ret=array();
        foreach ($problem as $value){
            $ret[]['title']=$value['notice_title'];
            $ret[]['content']=$value['notice_content'];
        }
        return $ret;
    }
}