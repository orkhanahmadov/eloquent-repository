<?php

namespace Orkhanahmadov\EloquentRepository\Tests\fixtures;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read int id
 * @property-read string name
 */
class ModelOne extends Model
{
    use SoftDeletes;

    protected $table = 'model_one';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    public function modelTwo()
    {
        return $this->hasMany(ModelTwo::class);
    }

    public function scopeIdGreaterThan10(Builder $builder)
    {
        return $builder->where('id', '>', 10);
    }

    public function scopeIdLessThanOrEqual20(Builder $builder)
    {
        return $builder->where('id', '<=', 20);
    }
}
