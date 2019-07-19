<?php

namespace Innoscripta\EloquentRepository\Tests;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    use SoftDeletes;

    protected $guarded = [];

    public $incrementing = false;

    public $timestamps = false;
}
