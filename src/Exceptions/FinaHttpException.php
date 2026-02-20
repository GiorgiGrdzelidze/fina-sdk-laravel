<?php

declare(strict_types=1);

/**
 * Exception for HTTP-level errors when communicating with the FINA API.
 */

namespace Fina\Sdk\Laravel\Exceptions;

use Throwable;

/**
 * Thrown when an HTTP request to the FINA API fails (network error, 4xx/5xx).
 *
 * Exposes the HTTP status code and raw response body for debugging.
 */
final class FinaHttpException extends FinaException
{
    /**
     * @param  int  $status  HTTP status code (0 if no response received).
     * @param  string|null  $body  Raw response body, if available.
     * @param  Throwable|null  $previous  The underlying HTTP client exception.
     */
    public function __construct(
        public readonly int $status,
        public readonly ?string $body = null,
        ?Throwable $previous = null
    ) {
        parent::__construct("FINA HTTP error: {$status}", $status, $previous);
    }
}
