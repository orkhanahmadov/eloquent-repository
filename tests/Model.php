<?php

namespace Innoscripta\EloquentRepository\Tests;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    protected $guarded = [];

    public $incrementing = false;

    public $timestamps = false;
}
