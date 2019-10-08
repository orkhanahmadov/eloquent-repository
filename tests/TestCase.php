<?php

namespace Orkhanahmadov\EloquentRepository\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Orchestra\Testbench\TestCase as Orchestra;
use Orkhanahmadov\EloquentRepository\EloquentRepository;
use Orkhanahmadov\EloquentRepository\EloquentRepositoryServiceProvider;
use Orkhanahmadov\EloquentRepository\Tests\fixtures\ModelOneRepository;

class TestCase extends Orchestra
{
    /**
     * @var EloquentRepository
     */
    protected $repository;
    /**
     * @var ModelOneRepository
     */
    protected $modelOneRepository;

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

    protected function setUp(): void
    {
        parent::setUp();

        DB::statement('CREATE TABLE model_one (id INT, name VARCHAR, deleted_at TIMESTAMP);');
        DB::statement('CREATE TABLE model_two (id INT, model_one_id INT, name VARCHAR);');

        $this->repository = $this->app->make(EloquentRepository::class);
        $this->modelOneRepository = $this->app->make(ModelOneRepository::class);
    }
}
