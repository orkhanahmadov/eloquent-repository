<?php

namespace Innoscripta\EloquentRepositoryGenerator\Repositories\Contracts;

interface Cachable
{
    /**
     * @return string
     */
    public function cacheKey(): string;

    /**
     * @return int
     */
    public function cacheTTL(): int;
}
