<?php

namespace Innoscripta\EloquentRepository\Tests;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model extends BaseModel
{
    use SoftDeletes;

    protected $guarded = [];

    public $incrementing = false;

    public $timestamps = false;
}
