<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Contracts;

interface Cacheable
{
    /**
     * Defines cache key.
     *
     * @return string
     */
    public function cacheKey(): string;

    /**
     * Cache time-to-live value in seconds.
     *
     * @param int $ttl
     *
     * @return int
     */
    public function cacheTTL(int $ttl): int;

    /**
     * Removes cache for model.
     *
     * @param $model
     */
    public function invalidateCache($model): void;
}
