<?php

namespace ResultSystems\Relationships;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use ResultSystems\Relationships\Traits\RelationshipsTrait;

abstract class Model extends EloquentModel
{
    use RelationshipsTrait;
}
