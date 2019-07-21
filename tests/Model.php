<?php

namespace Innoscripta\EloquentRepository\Tests;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model extends BaseModel
{
    use SoftDeletes;

    protected $guarded = [];

    public $incrementing = false;

    public $timestamps = false;

    public function relations()
    {
        return $this->hasMany(ModelRelation::class);
    }

    public function scopeIdGreaterThan10(Builder $builder)
    {
        return $builder->where('id', '>', 10);
    }

    public function scopeIdLessThan20(Builder $builder)
    {
        return $builder->where('id', '<', 20);
    }
}
