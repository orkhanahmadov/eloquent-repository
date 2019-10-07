<?php

namespace Orkhanahmadov\EloquentRepository\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;
use Orkhanahmadov\EloquentRepository\EloquentRepositoryServiceProvider;

class TestCase extends Orchestra
{
    /**
     * Resolve application aliases.
     *
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            EloquentRepositoryServiceProvider::class,
        ];
    }
}
