<?php

namespace Orkhanahmadov\EloquentRepository;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Orkhanahmadov\EloquentRepository\Console\RepositoryMakeCommand;

class ServiceProvider extends BaseServiceProvider
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
