<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/30
 * Time: 15:35
 */
namespace Micro\Common\Contract ;

use Micro\Common\Criteria\Criteria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

abstract class Repository {


    public $model;
    /**
     * __construct.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function setTable(String $table)
    {
        if($table != null){
            $this->model->setTable($table);
        }
        return $this;
    }

    public function insert($data)
    {
        return $this->newQuery()->insert($data);
    }

    //下面查询返回对象
    public function fetchId($id, $columns = ['*'])
    {
        return $this->newQuery()->find($id, $columns);
    }

    public function fetchOne($criteria = [], $columns = ['*'])
    {
        return $this->matching($criteria)->first($columns);
    }

    public function fetch($criteria = [], $columns = ['*'])
    {
        return $this->matching($criteria)->get($columns);
    }
    //下面查询返回数组
    public function find($id, $columns = ['*'])
    {
        return optional($this->newQuery()->find($id, $columns))->toArray();
    }

    public function first($criteria = [], $columns = ['*'])
    {
        return optional($this->matching($criteria)->first($columns))->toArray();
    }

    public function get($criteria = [], $columns = ['*'])
    {
        return optional($this->matching($criteria)->get($columns))->toArray();
    }

    public function update($id, $attributes)
    {
        return $this->forceUpdate($id,$attributes);
    }

    /**
     * delete.
     *
     * @param  mixed $id
     * @return bool|null
     */
    public function delete($id)
    {
        return $this->fetchId($id)->delete();
    }

    /**
     * Find multiple models by their primary keys.
     *
     * @param  \Illuminate\Contracts\Support\Arrayable|array  $ids
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findMany($ids, $columns = ['*'])
    {
        return optional($this->newQuery()->findMany($ids, $columns))->toArray();
    }
    /**
     * Find a model by its primary key or throw an exception.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail($id, $columns = ['*'])
    {
        return $this->newQuery()->findOrFail($id, $columns);
    }
    /**
     * Find a model by its primary key or return fresh model instance.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrNew($id, $columns = ['*'])
    {
        return $this->newQuery()->findOrNew($id, $columns);
    }
    /**
     * Get the first record matching the attributes or instantiate it.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function firstOrNew(array $attributes, array $values = [])
    {
        return $this->newQuery()->firstOrNew($attributes, $values);
    }
    /**
     * Get the first record matching the attributes or create it.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function firstOrCreate(array $attributes, array $values = [])
    {
        return $this->newQuery()->firstOrCreate($attributes, $values);
    }
    /**
     * Create or update a record matching the attributes, and fill it with values.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        return $this->newQuery()->updateOrCreate($attributes, $values);
    }
    /**
     * Execute the query and get the first result or throw an exception.
     *
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|static
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function firstOrFail($columns = ['*'])
    {
        return $this->newQuery()->firstOrFail($columns);
    }
    /**
     * Mass 创建用户.
     *
     * @param  array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Throwable
     */
    public function create($attributes)
    {
        return tap($this->newInstance(), function ($instance) use ($attributes) {
            $instance->fill($attributes)->saveOrFail();
        });
    }
    /**
     * Save a new model and return the instance.
     *
     * @param  array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Throwable
     */
    public function forceCreate($attributes)
    {
        return tap($this->newInstance(), function ($instance) use ($attributes) {
            $instance->forceFill($attributes)->saveOrFail();
        });
    }



    /**
     * update.
     *
     * @param  array $attributes
     * @param  mixed $id
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Throwable
     */
    public function fill($id, $attributes)
    {
        return tap($this->findOrFail($id), function ($instance) use ($attributes) {
            $instance->fill($attributes)->saveOrFail();
        });
    }
    /**
     * forceCreate.
     *
     * @param  array $attributes
     * @param  mixed $id
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Throwable
     */
    public function forceUpdate($id, $attributes)
    {
        return tap($this->findOrFail($id), function ($instance) use ($attributes) {
            $instance->forceFill($attributes)->saveOrFail();
        });
    }

    /**
     * Restore a soft-deleted model instance.
     *
     * @param  mixed $id
     * @return bool|null
     */
    public function restore($id)
    {
        return $this->newQuery()->restore($id);
    }
    /**
     * Force a hard delete on a soft deleted model.
     *
     * This method protects developers from running forceDelete when trait is missing.
     *
     * @param  mixed $id
     * @return bool|null
     */
    public function forceDelete($id)
    {
        return $this->findOrFail($id)->forceDelete();
    }
    /**
     * Create a new model instance that is existing.
     *
     * @param  array  $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function newInstance($attributes = [], $exists = false)
    {
        return $this->getModel()->newInstance($attributes, $exists);
    }


    public function chunk($criteria, $count, callable $callback)
    {
        return $this->matching($criteria)->chunk($count, $callback);
    }


    public function each($criteria, callable $callback, $count = 1000)
    {
        return $this->matching($criteria)->each($callback, $count);
    }



    public function paginate($criteria = [], $perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        return $this->matching($criteria)->paginate($perPage, $columns, $pageName, $page);
    }


    public function simplePaginate($criteria = [], $perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        return $this->matching($criteria)->simplePaginate($perPage, $columns, $pageName, $page);
    }


    public function count($criteria = [], $columns = '*')
    {
        return (int) $this->matching($criteria)->count($columns);
    }


    public function min($criteria, $column)
    {
        return $this->matching($criteria)->min($column);
    }


    public function max($criteria, $column)
    {
        return $this->matching($criteria)->max($column);
    }


    public function sum($criteria, $column)
    {
        $result = $this->matching($criteria)->sum($column);
        return $result ?: 0;
    }


    public function avg($criteria, $column)
    {
        return $this->matching($criteria)->avg($column);
    }


    public function average($criteria, $column)
    {
        return $this->avg($criteria, $column);
    }


    public function matching($criteria)
    {
        $criteria = is_array($criteria) === false ? [$criteria] : $criteria;
        return array_reduce($criteria, function ($query, $criteria) {
            $criteria->each(function ($method) use ($query) {
                call_user_func_array([$query, $method->name], $method->parameters);
            });
            return $query;
        }, $this->newQuery());
    }


    public function getQuery($criteria = [])
    {
        return $this->matching($criteria)->getQuery();
    }


    public function getModel()
    {
        return $this->model instanceof Model
            ? clone $this->model
            : $this->model->getModel();
    }


    public function newQuery()
    {
        return $this->model instanceof Model
            ? $this->model->newQuery()
            : clone $this->model;
    }

    public function __toString()
    {
        return get_called_class();
    }


    /**
     * @param $name
     * @param $life
     * @param $criteria
     * @param array $columns
     * @return mixed
     * @desc 缓存多行
     */
    public function cacheGet($name,$life,$criteria,$columns=['*'])
    {
        if(config('cache.repo.status')){
            $table = $this->model->getTable();
            $name = $table."_".md5(json_encode($name));
            return Cache::remember($name,$life,function() use($criteria,$columns){
                return $this->get($criteria,$columns);
            });
        }else{
            return $this->get($criteria,$columns);
        }
    }

    /**
     * @param $name
     * @param $life
     * @param $criteria
     * @param array $columns
     * @return mixed
     * @desc 缓存单行
     */
    public function cacheFirst($name,$life,$criteria,$columns=['*'])
    {
        if(config('cache.repo.status')){
            $table = $this->model->getTable();
            $name = env('QUEUE_NAME').'_'.$table."_".md5(json_encode($name));
            return Cache::remember($name,$life,function() use($criteria,$columns){
                return  $this->first($criteria,$columns);
            });
        }else{
            return  $this->first($criteria,$columns);
        }
    }


    public function countWhere($where = [])
    {
        $criteria = [];
        foreach($where as $k=>$v){
            $criteria[] = Criteria::create()->where($k,$v);
        }
        return $this->count($criteria);
    }

    /**
     * @desc 增量
     * @param array $where
     * @param $field
     * @param int $number
     * @return mixed
     */
    public function increment($where = [],$field,$number = 1){
        $criteria = Criteria::create()->where($where);
        return $this->matching($criteria)->increment($field,$number);
    }

    /**
     * @desc 减量
     * @param array $where
     * @param $field
     * @param int $number
     * @return mixed
     */
    public function decrement($where = [],$field,$number = 1){
        $criteria = Criteria::create()->where($where);
        return $this->matching($criteria)->decrement($field,$number);
    }


    //自定义criteria查询
    public function FQL($request)
    {
        try {
            $criteria = Criteria::create();
            $condition = $request['condition'] ?? [];
            $columns = $request['columns'] ?? [];
            foreach ($condition as $value) {
                foreach ($value as $k => $v) {
                    $criteria->{$k}(...$v);
                }
            }
            $query = $request['method'] ?? '';
            return $this->{$query}($criteria, $columns);
        }catch (\Exception $e){
            Err('FQL错误');
        }
    }



}