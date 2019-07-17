<?php

namespace Innoscripta\EloquentRepositoryGenerator\Repositories\Eloquent\Criteria;

use Illuminate\Support\Arr;
use Innoscripta\EloquentRepositoryGenerator\Repositories\Criteria\Criterion;

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
