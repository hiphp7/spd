<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/7/10
 * Time: 09:49
 */

namespace Tests;


class IndexTest extends TestCase
{
    public function testIndex()
    {
        $this->get('/')->seeJson([
            'code' =>'0000'
        ]);

    }
}