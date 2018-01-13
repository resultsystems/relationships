<?php

namespace ResultSystems\Relationships;

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
     * @param string                                $firstKey
     * @param string                                $secondKey
     */
    public function __construct(Builder $query, Model $farParent, $firstKey = null, $secondKey = null)
    {
        parent::__construct($query, $farParent, null, null);
    }

    /**
     * Set the base constraints on the relation query.
     */
    public function addConstraints()
    {
    }
}
