<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Eloquent\Criteria;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Orkhanahmadov\EloquentRepository\Repository\Criteria\Criterion;

class Scope implements Criterion
{
    /**
     * @var array
     */
    protected $scopes;

    /**
     * Scope constructor.
     * @param mixed ...$scopes
     */
    public function __construct(...$scopes)
    {
        $this->scopes = Arr::flatten($scopes);
    }

    /**
     * @param Builder|mixed $model
     *
     * @return Builder|mixed
     */
    public function apply($model)
    {
        foreach ($this->scopes as $scope) {
            $model = $model->{$scope}();
        }

        return $model;
    }
}
