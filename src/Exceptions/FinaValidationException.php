<?php

declare(strict_types=1);

/**
 * Exception for DTO payload validation failures.
 */

namespace Fina\Sdk\Laravel\Exceptions;

/**
 * Thrown when a DTO payload fails Laravel validation before being sent to the API.
 *
 * The `$errors` property contains the validation error array from Laravel's Validator.
 */
final class FinaValidationException extends FinaException
{
    /**
     * @param  array<string, list<string>>  $errors  Validation errors keyed by field name.
     * @param  string  $message  Human-readable summary.
     * @param  int  $code  Exception code.
     * @param  \Throwable|null  $previous  Previous exception.
     */
    public function __construct(
        public readonly array $errors,
        string $message = 'FINA SDK payload validation failed',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
