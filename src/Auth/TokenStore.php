<?php

declare(strict_types=1);

/**
 * Cache-based storage for the FINA API authentication token.
 */

namespace Fina\Sdk\Laravel\Auth;

use Illuminate\Support\Facades\Cache;

/**
 * Stores, retrieves, and invalidates the FINA API bearer token using Laravel's cache.
 */
final class TokenStore
{
    /**
     * @param  string  $cacheKey  Unique cache key (includes base URL + login hash).
     * @param  int  $ttlSeconds  Time-to-live for the cached token in seconds.
     */
    public function __construct(
        private readonly string $cacheKey,
        private readonly int $ttlSeconds
    ) {}

    /**
     * Retrieve the cached authentication token.
     *
     * @return string|null The token, or null if not cached / empty.
     */
    public function get(): ?string
    {
        $token = Cache::get($this->cacheKey);

        return is_string($token) && $token !== '' ? $token : null;
    }

    /**
     * Store an authentication token in the cache.
     *
     * @param  string  $token  The bearer token to cache.
     */
    public function put(string $token): void
    {
        Cache::put($this->cacheKey, $token, $this->ttlSeconds);
    }

    /**
     * Remove the cached authentication token.
     */
    public function forget(): void
    {
        Cache::forget($this->cacheKey);
    }
}
