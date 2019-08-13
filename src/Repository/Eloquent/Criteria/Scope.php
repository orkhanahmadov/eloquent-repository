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
     * @param Builder|mixed $entity
     *
     * @return Builder|mixed
     */
    public function apply($entity)
    {
        foreach ($this->scopes as $scope) {
            $entity = $entity->{$scope}();
        }

        return $entity;
    }
}
