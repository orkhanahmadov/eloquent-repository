<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Contracts;

interface Cachable
{
    /**
     * Defines cache key.
     *
     * @return string
     */
    public function cacheKey(): string;

    /**
     * Defines cache time-to-live seconds.
     *
     * @return int
     */
    public function cacheTTL(): int;

    /**
     * Removes cache for model.
     *
     * @param $model
     */
    public function forgetCache($model): void;
}
