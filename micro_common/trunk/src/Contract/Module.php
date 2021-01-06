<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/26
 * Time: 15:08
 */

namespace  Micro\Common\Contract;


abstract class Module {

    public $listen;
    public $subscribe;

    abstract public function registerEvent();
    abstract public function service($service);
    abstract public function getListen();
    abstract public function getSubscribe();
}