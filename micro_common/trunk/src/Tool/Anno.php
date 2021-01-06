<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/8/7
 * Time: 14:02
 */

namespace Micro\Common\Tool;


class Anno
{
    public $docComment=[];
    public function reflection($class,$method)
    {
        try{
           $rMethod =  new \ReflectionMethod($class,$method);
           $this->docComment  =  explode("\n", $rMethod->getDocComment());
           return $this;
        }catch (\Exception $ex){
            Err('找不到:'.$class.'的方法'.$method);
        }
    }



    public function getTitle()
    {
        $title = null;
        $result ='';
        foreach ($this->docComment as $comment) {
            $comment = trim($comment);
            //标题描述
            if (empty($title) && strpos($comment, '@') === FALSE && strpos($comment, '/') === FALSE) {
                $title = substr($comment, strpos($comment, '*') + 1);
                $result = trim($title);
                continue;
            }
        }
        return $result;
    }

    public function getDesc()
    {
        $result =[];
        foreach ($this->docComment as $comment) {
            $comment = trim($comment);
            $pos = stripos($comment, '@desc');
            if ($pos !== FALSE) {
                $descComment = substr($comment, $pos + 5);
                $result = trim($descComment);
                continue;
            }
        }
        return $result;
    }
    
    public function getParam()
    {
        return $this->parseComment('param');
    }

    public function getReturn()
    {
        return $this->parseComment('return');

    }

    public function parseComment($key)
    {
        $result = [];
        foreach ($this->docComment as $comment) {
            $comment = trim($comment);
            //@return注释
            $pos = stripos($comment, '@'.$key);
            if ($pos === FALSE) {
                continue;
            }
            $commentArr = explode(' ', substr($comment, $pos + strlen($key) + 1));
            //将数组中的空值过滤掉，同时将需要展示的值返回
            $commentArr = array_values(array_filter($commentArr));
            $ret =[
                'type'=>$commentArr[0],
                'arg' =>$commentArr[1]??'',
                'desc' =>$commentArr[2]??'',
                'rule' =>$commentArr[3]??''
            ];
            //以返回字段为key，保证覆盖
            $result[$ret['arg']] = $ret;
        }
        return $result;
    }
}