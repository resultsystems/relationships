<?php

namespace ResultSystems\Relationships\Traits;

class Helpers
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function getJoinsRelations(array $relations, $localKey = null, $reverse = false)
    {
        reset($relations);
        $queries = [];
        while ($current = current($relations)) {
            $model = $this->getRelationInstanceFromCurrent($current, $relations);
            $next = next($relations);
            if ($next) {
                $next = $this->getRelationInstanceFromCurrent($next, $relations);
            } else {
                $next = $this->model;
            }

            $queries[] = [
                //'model' => get_class($model),
                'table' => $next->getTable(),
                'key' => $this->getKeyNameFromModelOrData($model, $next, $current, $reverse),
                'foreign_key' => $this->getForeignKeyFromModelOrData($next, $model, $current, $reverse),
                'select_key' => $this->getSelectKeyFromModelOrData($next, $model, $current, $reverse),
            ];
        }

        return $queries;
    }

    public function getReverseJoinsRelations(array $relations, $localKey = null)
    {
        return $this->getJoinsRelations($relations, $localKey, true);
    }

    public function getClassNameFromCurrent($current, $relations)
    {
        if (is_array($current)) {
            return key($relations);
        }

        return $current;
    }

    public function getRelationInstanceFromCurrent($current, $relations)
    {
        if (is_array($current)) {
            $current = key($relations);
        }

        return new $current();
    }

    public function getKeyNameFromModelOrData($model, $nextModel, $current, $reverse = false)
    {
        if ($reverse) {
            return $this->getReverseKeyNameFromModelOrData($model, $nextModel, $current);
        }

        if (is_array($current)) {
            return current($current);
        }

        return $nextModel->getTable().'.'.$model->getForeignKey();
    }

    public function getReverseKeyNameFromModelOrData($model, $nextModel, $current)
    {
        if (is_array($current)) {
            return current($current);
        }

        return $model->getTable().'.'.$nextModel->getKeyName();
    }

    public function getSelectKeyFromModelOrData($model, $lastModel, $current, $reverse = false)
    {
        if (is_array($current)) {
            return key($current);
        }

        return $model->getTable().'.'.$model->getKeyName();
    }

    public function getForeignKeyFromModelOrData($model, $lastModel, $current, $reverse = false)
    {
        if ($reverse) {
            return $this->getReverseForeignKeyFromModelOrData($model, $lastModel, $current);
        }

        if (is_array($current)) {
            return key($current);
        }

        return $lastModel->getTable().'.'.$model->getKeyName();
    }

    public function getReverseForeignKeyFromModelOrData($model, $lastModel, $current)
    {
        if (is_array($current)) {
            return key($current);
        }

        return $model->getTable().'.'.$lastModel->getForeignKey();
    }

    public function getKeyName($model, $key = null)
    {
        return $key ?? $model->getKeyName;
    }
}
