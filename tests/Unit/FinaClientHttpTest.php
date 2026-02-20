<?php

declare(strict_types=1);

namespace Fina\Sdk\Laravel\Tests\Unit;

use Fina\Sdk\Laravel\Client\FinaClient;
use Fina\Sdk\Laravel\Exceptions\FinaHttpException;
use Fina\Sdk\Laravel\Tests\TestCase;
use Illuminate\Support\Facades\Http;

final class FinaClientHttpTest extends TestCase
{
    private function fakeAuthResponse(string $token = 'fake-token-123'): array
    {
        return ['token' => $token, 'ex' => null];
    }

    private function makeClient(): FinaClient
    {
        return $this->app->make(FinaClient::class);
    }

    // -----------------------------------------------------------------
    // A) Token caching
    // -----------------------------------------------------------------

    public function test_auth_service_requests_token_and_caches_it(): void
    {
        Http::fake([
            '*/api/authentication/authenticate' => Http::response($this->fakeAuthResponse(), 200),
            '*/api/operation/getCustomers' => Http::response(['customers' => [], 'ex' => null], 200),
        ]);

        $client = $this->makeClient();

        // First call — triggers auth + API call
        $result = $client->request('get', '/api/operation/getCustomers');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('customers', $result);

        // Token should now be cached
        $this->assertNotNull($client->tokenStore()->get());
        $this->assertSame('fake-token-123', $client->tokenStore()->get());
    }

    public function test_second_call_reuses_cached_token(): void
    {
        Http::fake([
            '*/api/authentication/authenticate' => Http::response($this->fakeAuthResponse(), 200),
            '*/api/operation/getCustomers' => Http::response(['customers' => [], 'ex' => null], 200),
        ]);

        $client = $this->makeClient();

        // Two calls
        $client->request('get', '/api/operation/getCustomers');
        $client->request('get', '/api/operation/getCustomers');

        // Auth endpoint should only be called once
        Http::assertSentCount(3); // 1 auth + 2 API calls
    }

    // -----------------------------------------------------------------
    // B) Auto-refresh on 401
    // -----------------------------------------------------------------

    public function test_auto_refresh_on_401(): void
    {
        $callCount = 0;

        Http::fake(function ($request) use (&$callCount) {
            // Auth endpoint
            if (str_contains($request->url(), '/api/authentication/authenticate')) {
                $callCount++;
                $token = $callCount === 1 ? 'old-token' : 'new-token';

                return Http::response(['token' => $token, 'ex' => null], 200);
            }

            // API endpoint — first call returns 401, subsequent calls return 200
            if (str_contains($request->url(), '/api/operation/getCustomers')) {
                $authHeader = $request->header('Authorization')[0] ?? '';

                if (str_contains($authHeader, 'old-token')) {
                    return Http::response(['error' => 'Unauthorized'], 401);
                }

                return Http::response(['customers' => ['refreshed'], 'ex' => null], 200);
            }

            return Http::response([], 404);
        });

        $client = $this->makeClient();
        $result = $client->request('get', '/api/operation/getCustomers');

        $this->assertSame(['refreshed'], $result['customers']);
        $this->assertSame('new-token', $client->tokenStore()->get());
    }

    // -----------------------------------------------------------------
    // C) Non-401 errors throw FinaHttpException
    // -----------------------------------------------------------------

    public function test_500_throws_fina_http_exception(): void
    {
        Http::fake([
            '*/api/authentication/authenticate' => Http::response($this->fakeAuthResponse(), 200),
            '*/api/operation/getCustomers' => Http::response('Internal Server Error', 500),
        ]);

        $client = $this->makeClient();

        $this->expectException(FinaHttpException::class);

        $client->request('get', '/api/operation/getCustomers');
    }

    public function test_http_exception_contains_status_and_body(): void
    {
        Http::fake([
            '*/api/authentication/authenticate' => Http::response($this->fakeAuthResponse(), 200),
            '*/api/operation/getCustomers' => Http::response('Bad Request Body', 400),
        ]);

        $client = $this->makeClient();

        try {
            $client->request('get', '/api/operation/getCustomers');
            $this->fail('Expected FinaHttpException');
        } catch (FinaHttpException $e) {
            $this->assertSame(400, $e->status);
            $this->assertSame('Bad Request Body', $e->body);
        }
    }
}
