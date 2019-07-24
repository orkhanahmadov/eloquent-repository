<?php

namespace Orkhanahmadov\EloquentRepository\Tests;

use Illuminate\Foundation\Application;
use Orkhanahmadov\EloquentRepository\ServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
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
            ServiceProvider::class,
        ];
    }
}
