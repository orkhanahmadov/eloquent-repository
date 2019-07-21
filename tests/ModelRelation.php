<?php

namespace Innoscripta\EloquentRepository\Tests;

use Illuminate\Database\Eloquent\Model as BaseModel;

class ModelRelation extends BaseModel
{
    protected $guarded = [];

    public $incrementing = false;

    public $timestamps = false;
}
