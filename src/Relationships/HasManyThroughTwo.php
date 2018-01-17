<?php

namespace ResultSystems\Relationships;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough as LaravelHasManyThrough;

class HasManyThroughTwo extends LaravelHasManyThrough
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
     */
    public function __construct(Builder $query, Model $farParent, Model $throughParent, Model $throughSecondParent, $firstKey, $secondKey, $localKey, $secondLocalKey)
    {
        $this->secondParent = $throughSecondParent;
        parent::__construct($query, $farParent, $throughParent, $firstKey, $secondKey, $localKey, $secondLocalKey);
    }

    /**
     * Get the qualified foreign key on the related model.
     *
     * @return string
     */
    public function getQualifiedForeignKeyName()
    {
        return $this->secondParent->getTable().'.'.$this->secondParent->getKeyName();
    }

    /**
     * Get the fully qualified parent key name.
     *
     * @return string
     */
    public function getQualifiedParentKeyName()
    {
        return $this->parent->getTable().'.'.$this->secondParent->getForeignKey();
    }
}
