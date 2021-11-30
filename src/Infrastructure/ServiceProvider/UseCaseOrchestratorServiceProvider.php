<?php

namespace Ro\HexUseCaseOrchestrator\Infrastructure\ServiceProvider;

use Illuminate\Support\ServiceProvider;
use Ro\HexUseCaseOrchestrator\Infrastructure\Orchestrator\UseCaseOrchestrator;

class UseCaseOrchestratorServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(UseCaseOrchestrator::class, function ($app) {
            return new UseCaseOrchestrator($this->loadHandlers());
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/use-case-handlers.php' => config_path('use-case-handlers.php'),
        ], 'use-case-handlers');
    }

    public function loadHandlers()
    {
        return $this->app['config']->get('use-case-handlers');
    }
}