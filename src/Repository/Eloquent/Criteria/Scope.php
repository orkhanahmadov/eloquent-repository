<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Eloquent\Criteria;

use Illuminate\Support\Arr;
use Orkhanahmadov\EloquentRepository\Repository\Criteria\Criterion;

class Scope implements Criterion
{
    /**
     * @var array
     */
    private $scopes;

    public function __construct(...$scopes)
    {
        $this->scopes = Arr::flatten($scopes);
    }

    /**
     * @param $entity
     *
     * @return mixed
     */
    public function apply($entity)
    {
        foreach ($this->scopes as $scope) {
            $entity = $entity->{$scope}();
        }

        return $entity;
    }
}
