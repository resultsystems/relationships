<?php

namespace ResultSystems\Relationships\Traits;

class Helpers
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function getJoinsRelations(array $relations, $localKey = null)
    {
        // inner join `skills` on `subject_id` = `subjects`.`id`
        // inner join `schedules` on `skill_id` = `skills`.`id`
        // inner join `frequencies` on `schedule_id` = `schedules`.`id` where `frequencies`.`id` = ? and `subjects`.`deleted_at` is null limit 1

        // {"table":"skills","key":"skills.subject_id","foreign_key":"subjects.id"},
        // {"table":"schedules","key":"schedules.skill_id","foreign_key":"skills.id"},
        // {"table":"frequencies","key":"frequencies.schedule_id","foreign_key":"schedules.id"}

        // {"table":"skills","key":"skills.subject_id","foreign_key":"subjects.id"},
        // {"table":"schedules","key":"schedules.skill_id","foreign_key":"skills.id"},
        // {"table":"frequencies","key":"frequencies.schedule_id","foreign_key":"schedules.id"}

        // {"table":"skills","key":"skills.subject_id","foreign_key":"subjects.id"},
        // {"table":"schedules","key":"schedules.skill_id","foreign_key":"skills.id"},
        // {"table":"frequencies","key":"frequencies.schedule_id","foreign_key":"schedules.id"}

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
                'key' => $this->getKeyNameFromModelOrData($model, $next, $current),
                'foreign_key' => $this->getForeignKeyFromModelOrData($next, $model, $current),
            ];
        }

        return $queries;
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

    public function getKeyNameFromModelOrData($model, $nextModel, $current)
    {
        if (is_array($current)) {
            return current($current);
        }

        return $nextModel->getTable().'.'.$model->getForeignKey();
    }

    public function getForeignKeyFromModelOrData($model, $lastModel, $current)
    {
        if (is_array($current)) {
            return key($current);
        }

        return $lastModel->getTable().'.'.$model->getKeyName();
    }

    public function getKeyName($model, $key = null)
    {
        return $key ?? $model->getKeyName;
    }
}
