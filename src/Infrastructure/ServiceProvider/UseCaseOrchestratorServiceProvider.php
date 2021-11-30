<?php

namespace Ro\HexUseCaseOrchestrator\Infrastructure\ServiceProvider;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Ro\HexUseCaseOrchestrator\Infrastructure\Orchestrator\UseCaseOrchestrator;

class UseCaseOrchestratorServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            $this->configPath(), 'use-case-handlers'
        );
        $this->app->singleton(UseCaseOrchestrator::class, function ($app) {
            return new UseCaseOrchestrator($this->loadHandlers());
        });
    }

    public function boot()
    {
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {

            $this->publishes([
                $this->configPath() => config_path('use-case-handlers.php'),
            ], 'use-case-handlers');
        }
    }

    public function loadHandlers()
    {
        return $this->app['config']->get('use-case-handlers');
    }

    function configPath(): string
    {
        return __DIR__ . '/../../../config/use-case-handlers.php';
    }
}