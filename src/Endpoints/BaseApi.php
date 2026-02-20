<?php

declare(strict_types=1);

/**
 * Abstract base for all FINA API endpoint clients.
 */

namespace Fina\Sdk\Laravel\Endpoints;

use Fina\Sdk\Laravel\Client\FinaClient;
use Fina\Sdk\Laravel\Exceptions\FinaHttpException;
use Fina\Sdk\Laravel\Exceptions\FinaRemoteException;
use Fina\Sdk\Laravel\Support\ResponseGuard;

/**
 * Base class for FINA API endpoint clients.
 *
 * Provides URL construction, authorized GET/POST helpers, and automatic
 * `ex` field checking via {@see ResponseGuard}.
 */
abstract class BaseApi
{
    /**
     * @param  FinaClient  $client  The parent FINA client instance.
     * @param  string  $prefixKey  Config key for the API prefix (e.g. 'operation', 'reporting').
     */
    public function __construct(
        protected readonly FinaClient $client,
        protected readonly string $prefixKey
    ) {}

    /**
     * Build a full API path by prepending the configured prefix.
     *
     * @param  string  $path  Relative endpoint path (e.g. 'getCustomers').
     * @return string Full path (e.g. '/api/operation/getCustomers').
     */
    protected function url(string $path): string
    {
        $prefix = $this->client->prefix($this->prefixKey);
        $path = ltrim($path, '/');

        return $prefix !== '' ? "{$prefix}/{$path}" : $path;
    }

    /**
     * Send an authorized GET request and check for FINA errors.
     *
     * @param  string  $path  Relative endpoint path.
     * @param  array<string, mixed>  $query  Query parameters.
     * @param  string|null  $context  Error context message.
     * @return array<string, mixed> Decoded JSON response.
     *
     * @throws FinaHttpException On HTTP failure.
     * @throws FinaRemoteException If the response contains a non-null `ex` field.
     */
    protected function get(string $path, array $query = [], ?string $context = null): array
    {
        $data = $this->client->request('get', $this->url($path), $query);
        ResponseGuard::throwIfEx($data, $context ?? "{$this->prefixKey}.{$path} returned ex");

        return $data;
    }

    /**
     * Send an authorized POST request and check for FINA errors.
     *
     * @param  string  $path  Relative endpoint path.
     * @param  array<string, mixed>  $body  Request body.
     * @param  string|null  $context  Error context message.
     * @return array<string, mixed> Decoded JSON response.
     *
     * @throws FinaHttpException On HTTP failure.
     * @throws FinaRemoteException If the response contains a non-null `ex` field.
     */
    protected function post(string $path, array $body = [], ?string $context = null): array
    {
        $data = $this->client->request('post', $this->url($path), $body);
        ResponseGuard::throwIfEx($data, $context ?? "{$this->prefixKey}.{$path} returned ex");

        return $data;
    }
}
