<?php

namespace ResultSystems\Relationships;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne as LaravelHasOne;

class HasOneThroughSeveral extends LaravelHasOne
{
    /**
     * Create a new has many throught threee relationship instance.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model   $farParent
     * @param string                                $foreignKey
     * @param string                                $localKey
     */
    public function __construct(Builder $query, Model $farParent, $foreignKey = null, $localKey = null)
    {
        $this->localKey = $localKey;
        $this->foreignKey = $foreignKey;
        $this->farParent = $farParent;

        parent::__construct($query, $farParent, $foreignKey, $localKey);
    }

    /**
     * Set the base constraints on the relation query.
     */
    public function addConstraints()
    {
        if (static::$constraints) {
            $this->query->where($this->foreignKey, '=', $this->getParentKey());
        }
    }

    /**
     * Match the eagerly loaded results to their many parents.
     *
     * @param array                                    $models
     * @param \Illuminate\Database\Eloquent\Collection $results
     * @param string                                   $relation
     * @param string                                   $type
     *
     * @return array
     */
    protected function matchOneOrMany(array $models, Collection $results, $relation, $type)
    {
        $dictionary = $this->buildDictionary($results);

        // Once we have the dictionary we can simply spin through the parent models to
        // link them up with their children using the keyed dictionary to make the
        // matching very convenient and easy work. Then we'll just return them.
        $model = current($models);
        $key = key($dictionary);
        if (isset($dictionary[$key])) {
            $model->setRelation(
                $relation,
                $this->getRelationValue($dictionary, $key, $type)
            );
        }

        return $models;
    }
}
