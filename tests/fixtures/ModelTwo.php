<?php

namespace Orkhanahmadov\EloquentRepository\Tests\fixtures;

use Illuminate\Database\Eloquent\Model;

class ModelTwo extends Model
{
    protected $table = 'model_two';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];
}
