<?php

namespace Orkhanahmadov\EloquentRepository;

use Illuminate\Support\ServiceProvider;
use Orkhanahmadov\EloquentRepository\Console\RepositoryMakeCommand;

class EloquentRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                RepositoryMakeCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //
    }
}
