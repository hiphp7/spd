<?php

namespace Micro\Common\Criteria;
use Closure;

class Criteria
{
    use Concerns\BuildsQueries,
        Concerns\QueriesRelationships,
        Concerns\EloquentBuildsQueries,
        Concerns\SoftDeletingScope;

    /**
     * $methods.
     *
     * @var \Micro\Common\Criteria\Method[]
     */
    protected $methods = [];

    /**
     * create.
     *
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * alias raw.
     *
     * @param mixed $value
     * @return Expression
     */
    public static function expr($value)
    {
        return static::raw($value);
    }

    /**
     * @param mixed $value
     * @return \Micro\Common\Criteria\Expression
     */
    public static function raw($value)
    {
        return new Expression($value);
    }

    /**
     * each.
     *
     * @param  Closure $callback
     * @return void
     */
    public function each(Closure $callback)
    {
        foreach ($this->methods as $method) {
            $callback($method);
        }
    }

    /**
     * toArray.
     *
     * @return array
     */
    public function toArray()
    {
        return array_map(function ($method) {
            return [
                'method' => $method->name,
                'parameters' => $method->parameters,
            ];
        }, $this->methods);
    }
}
