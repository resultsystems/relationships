<?php

namespace ResultSystems\Relationships;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne as LaravelHasOne;

class HasOneThroughSeveral extends LaravelHasOne
{
    protected $secondParent;

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
     * Initialize the relation on a set of models.
     *
     * @param array  $models
     * @param string $relation
     *
     * @return array
     */
    public function initRelation(array $models, $relation)
    {
        throw new Exception("Don't use with or load");
    }
}
