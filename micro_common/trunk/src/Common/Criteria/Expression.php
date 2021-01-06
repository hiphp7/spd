<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/2/25
 * Time: 17:00
 */

namespace Micro\Common\Common\Criteria;
use Illuminate\Database\Query\Expression as QueryExpression;

class Expression extends QueryExpression
{
    /**
     * The value of the expression.
     *
     * @var mixed
     */
    protected $value;
    /**
     * Create a new raw query expression.
     *
     * @param  mixed  $value
     * @return void
     */
    public function __construct($value)
    {
        $this->value = $value;
    }
    /**
     * Get the value of the expression.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getValue();
    }
    /**
     * Get the value of the expression.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}