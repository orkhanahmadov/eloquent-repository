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
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('eloquent-repository.php'),
            ], 'config');

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
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'eloquent-repository');
    }
}
