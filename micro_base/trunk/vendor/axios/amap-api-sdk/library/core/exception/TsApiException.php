<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/11
 * Time: 18:03
 */

namespace amap\sdk\core\exception;


use amap\sdk\core\AMapException;

class TsApiException extends AMapException
{
    public function __construct($errorMessage, $errorCode = 0)
    {
        parent::__construct($errorMessage, $errorCode);
        $this->setErrorType("TsAPI");
    }
}