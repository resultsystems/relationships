<?php

namespace ResultSystems\Relationships\Traits;

use ResultSystems\Relationships\HasManyThroughSeveral;
use ResultSystems\Relationships\HasOneThroughSeveral;

trait RelationshipsTrait
{
    /**
     * Define a has-many-through-several relationship.
     *
     * @param string      $related
     * @param string      $through
     * @param string      $through
     * @param string      $throughSecond
     * @param null|string $firstKey
     * @param null|string $secondKey
     * @param null|string $thirdKey
     * @param null|string $localKey
     * @param null|string $secondLocalKey
     * @param null|string $thirdLocalKey
     * @param bool        $distinct
     * @param array       $where
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function hasManyThroughSeveral($related, $through, $throughSecond, $firstKey = null, $secondKey = null, $thirdKey = null, $localKey = null, $secondLocalKey = null, $thirdLocalKey = null, $distinct = true, $where = [])
    {
        // Example
        // model = group
        // group.id = schedule.group_id
        // skill.id = schedule.skill_id
        // teacher.id = skill.teacher_id

        $instance = $this->newRelatedInstance($related);

        $throughSecond = new $throughSecond();
        $through = new $through();

        $firstKey = $firstKey ?: $this->getForeignKey();

        $secondKey = $secondKey ?: $throughSecond->getForeignKey();

        $thirdKey = $thirdKey ?: ($through->getTable().'.'.$instance->getForeignKey());

        $localKey = $localKey ?: $this->getKeyName();

        $secondLocalKey = $secondLocalKey ?: $throughSecond->getKeyName();

        $thirdLocalKey = $thirdLocalKey ?: ($instance->getTable().'.'.$instance->getKeyName());

        $query = $instance
            ->newQuery();
        if ($distinct) {
            $query->distinct();
        }

        if (!empty($where)) {
            $query->where($where);
        }

        $query->join($through->getTable(), $thirdKey, '=', $thirdLocalKey);

        return new HasManyThroughSeveral($query, $this, $throughSecond, $through, $firstKey, $secondKey, $localKey, $secondLocalKey);
    }

    /**
     * Define a one-to-one relationship.
     *
     * @param array  $relations
     * @param string $localKey
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function hasOneThroughSeveral(array $relations, $localKey = null)
    {
        $helpers = new Helpers($this);
        $currentRelation = current($relations);
        $related = $helpers->getClassNameFromCurrent($currentRelation, $relations);

        $instance = $this->newRelatedInstance($related);
        $query = $instance
            ->newQuery();

        $joins = $helpers->getJoinsRelations($relations, $localKey);
        foreach ($joins as $join) {
            $query->join($join['table'], $join['key'], '=', $join['foreign_key']);
        }

        $lastRelation = end($relations);

        $lastKey = $helpers->getKeyNameFromModelOrData($this, $this, $lastRelation);

        $key = $localKey ?? $this->getKeyName();

        $query->where($this->getTable().'.'.$key, $this->getAttribute($key));

        return new HasOneThroughSeveral($query, $this);
    }
}
