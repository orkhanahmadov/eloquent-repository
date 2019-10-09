<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Orkhanahmadov\EloquentRepository\Repository\Contracts\Criterion;

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
     * @param Builder $model
     *
     * @return Builder
     */
    public function apply($model)
    {
        foreach ($this->scopes as $scope) {
            $model = $model->{$scope}();
        }

        return $model;
    }
}
