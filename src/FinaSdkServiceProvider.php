<?php

declare(strict_types=1);

/**
 * Laravel service provider for the FINA SDK package.
 */

namespace Fina\Sdk\Laravel;

use Fina\Sdk\Laravel\Client\FinaClient;
use Illuminate\Support\ServiceProvider;

/**
 * Registers the {@see FinaClient} singleton and publishes the configuration file.
 *
 * Resolved via `app(FinaClient::class)` or `app('fina')`.
 */
final class FinaSdkServiceProvider extends ServiceProvider
{
    /**
     * Register the FINA SDK bindings and configuration.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/fina.php', 'fina');

        $this->app->singleton(FinaClient::class, function () {
            return new FinaClient(config('fina'));
        });

        $this->app->alias(FinaClient::class, 'fina');
    }

    /**
     * Publish the FINA SDK configuration file.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/fina.php' => config_path('fina.php'),
        ], 'fina-config');
    }
}
