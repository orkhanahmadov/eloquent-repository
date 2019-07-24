<?php

namespace Orkhanahmadov\EloquentRepository\Tests;

use Illuminate\Database\Eloquent\Model as BaseModel;

class ModelRelation extends BaseModel
{
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];
}
