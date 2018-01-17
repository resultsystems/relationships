<?php

namespace ResultSystems\Relationships;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany as LaravelHasMany;

class HasManyThroughSeveral extends LaravelHasMany
{
    protected $secondParent;

    /**
     * Create a new has many throught threee relationship instance.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model   $farParent
     * @param \Illuminate\Database\Eloquent\Model   $throughParent
     * @param \Illuminate\Database\Eloquent\Model   $throughSecondParent
     * @param string                                $firstKey
     * @param string                                $secondKey
     * @param string                                $localKey
     * @param string                                $secondLocalKey
     * @param mixed                                 $foreignKey
     */
    public function __construct(Builder $query, Model $parent, $localKey)
    {
        $this->localKey = $localKey;

        parent::__construct($query, $parent, null, $localKey);
    }

    /**
     * Set the base constraints on the relation query.
     */
    public function addConstraints()
    {
    }
}
