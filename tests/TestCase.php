<?php

declare(strict_types=1);

namespace Fina\Sdk\Laravel\Tests;

use Fina\Sdk\Laravel\FinaSdkServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            FinaSdkServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('fina.base_url', 'http://fina-test.local:5007');
        $app['config']->set('fina.login', 'test_user');
        $app['config']->set('fina.password', 'test_pass');
        $app['config']->set('fina.timeout', 10);
        $app['config']->set('fina.retry_times', 0);
        $app['config']->set('fina.retry_sleep_ms', 0);
        $app['config']->set('fina.token_cache_ttl_seconds', 3600);
        $app['config']->set('fina.token_cache_key_prefix', 'fina:sdk:test:token:');
        $app['config']->set('fina.prefixes', [
            'authentication' => '/api/authentication',
            'operation' => '/api/operation',
            'reporting' => '/api/reporting',
        ]);
        $app['config']->set('cache.default', 'array');
    }
}
