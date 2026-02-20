<?php

declare(strict_types=1);

/**
 * DTO for the FINA authentication endpoint response.
 */

namespace Fina\Sdk\Laravel\Auth;

/**
 * Represents the JSON response from `POST /api/authentication/authenticate`.
 */
final readonly class AuthTokenResponse
{
    /**
     * @param  string|null  $token  The bearer token (null if authentication failed).
     * @param  mixed  $ex  The FINA error field (null on success).
     */
    public function __construct(
        public ?string $token,
        public mixed $ex
    ) {}

    /**
     * Build from raw FINA API response array.
     *
     * @param  array{token?: string, ex?: mixed}  $data
     */
    public static function fromArray(array $data): self
    {
        $token = isset($data['token']) && is_string($data['token']) && $data['token'] !== ''
            ? $data['token']
            : null;

        return new self($token, $data['ex'] ?? null);
    }
}
