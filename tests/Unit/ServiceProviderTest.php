<?php

declare(strict_types=1);

namespace Fina\Sdk\Laravel\Tests\Unit;

use Fina\Sdk\Laravel\Client\FinaClient;
use Fina\Sdk\Laravel\Tests\TestCase;

final class ServiceProviderTest extends TestCase
{
    public function test_fina_client_is_bound_as_singleton(): void
    {
        $client = $this->app->make(FinaClient::class);

        $this->assertInstanceOf(FinaClient::class, $client);
        $this->assertSame($client, $this->app->make(FinaClient::class));
    }

    public function test_fina_alias_resolves_to_client(): void
    {
        $client = $this->app->make('fina');

        $this->assertInstanceOf(FinaClient::class, $client);
    }

    public function test_config_defaults_are_loaded(): void
    {
        $this->assertSame('http://fina-test.local:5007', config('fina.base_url'));
        $this->assertSame('test_user', config('fina.login'));
        $this->assertSame('test_pass', config('fina.password'));
        $this->assertSame('/api/authentication', config('fina.prefixes.authentication'));
        $this->assertSame('/api/operation', config('fina.prefixes.operation'));
        $this->assertSame('/api/reporting', config('fina.prefixes.reporting'));
    }

    public function test_client_exposes_config_values(): void
    {
        /** @var FinaClient $client */
        $client = $this->app->make(FinaClient::class);

        $this->assertSame('http://fina-test.local:5007', $client->baseUrl());
        $this->assertSame('test_user', $client->login());
        $this->assertSame('test_pass', $client->password());
        $this->assertSame('/api/authentication', $client->prefix('authentication'));
        $this->assertSame('/api/operation', $client->prefix('operation'));
        $this->assertSame('/api/reporting', $client->prefix('reporting'));
    }
}
