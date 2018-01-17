<?php

namespace ResultSystems\Relationships\Traits;

use ResultSystems\Relationships\HasManyThroughSeveral;
use ResultSystems\Relationships\HasManyThroughTwo;
use ResultSystems\Relationships\HasOneThroughSeveral;

trait RelationshipsTrait
{
    /**
     * Define a has-many-through-two relationship.
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
    public function hasManyThroughTwo($related, $through, $throughSecond, $firstKey = null, $secondKey = null, $thirdKey = null, $localKey = null, $secondLocalKey = null, $thirdLocalKey = null, $distinct = true, $where = [])
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

        return new HasManyThroughTwo($query, $this, $throughSecond, $through, $firstKey, $secondKey, $localKey, $secondLocalKey);
    }

    /**
     * Define a one-to-many-through-several relationship.
     *
     * @param array  $relations
     * @param string $foreignKey
     * @param string $localKey
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hasManyThroughSeveral(array $relations, $foreignKey = null, $localKey = null)
    {
        $helpers = new Helpers($this);
        $currentRelation = current($relations);
        $related = $helpers->getClassNameFromCurrent($currentRelation, $relations);
        $firstRelation = new $related();

        $instance = $this->newRelatedInstance($related);
        $query = $instance
            ->newQuery()
            ->distinct()
            ->select($firstRelation->getTable().'.*');

        $joins = $helpers->getReverseJoinsRelations($relations, $localKey);
        foreach ($joins as $join) {
            $query->join($join['table'], $join['key'], '=', $join['foreign_key']);
        }

        $query->addSelect($join['select_key']);

        $key = $localKey ?? $this->getKeyName();

        $foreignKey = $foreignKey ?? $join['foreign_key'];

        $localKey = $localKey ?? $this->getKeyName();

        return new HasManyThroughSeveral($query, $this, $foreignKey, $localKey);
    }

    /**
     * Define a one-to-one-through-several relationship.
     *
     * @param array  $relations
     * @param string $foreignKey
     * @param string $localKey
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function hasOneThroughSeveral(array $relations, $foreignKey = null, $localKey = null)
    {
        $helpers = new Helpers($this);
        $currentRelation = current($relations);
        $related = $helpers->getClassNameFromCurrent($currentRelation, $relations);
        $firstRelation = new $related();

        $instance = $this->newRelatedInstance($related);
        $query = $instance
            ->newQuery()
            ->select($firstRelation->getTable().'.*');

        $joins = $helpers->getJoinsRelations($relations, $localKey);
        foreach ($joins as $join) {
            $query->join($join['table'], $join['key'], '=', $join['foreign_key']);
            // $query->addSelect($join['select_key']);
            // $query->addSelect($join['select_key'].' as has_one_'.$join['select_key']);
            // $query->addSelect($join['select_key'].' as has_one_'.str_replace('.', '_', $join['select_key']));
        }

        $key = $localKey ?? $this->getKeyName();

        $foreignKey = $foreignKey ?? $this->getTable().'.'.$firstRelation->getKeyName();

        $localKey = $localKey ?? $firstRelation->getKeyName();

        return new HasOneThroughSeveral($query, $this, $foreignKey, $localKey);
    }
}
