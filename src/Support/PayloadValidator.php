<?php

declare(strict_types=1);

/**
 * Laravel validation runner for FINA SDK payload DTOs.
 */

namespace Fina\Sdk\Laravel\Support;

use Fina\Sdk\Laravel\Contracts\ValidatesPayload;
use Fina\Sdk\Laravel\Exceptions\FinaValidationException;
use Illuminate\Support\Facades\Validator;

/**
 * Validates a {@see ValidatesPayload} DTO using Laravel's Validator.
 *
 * Called automatically by API clients before sending payloads to the FINA API.
 * Can also be invoked manually for early validation.
 */
final class PayloadValidator
{
    /**
     * Validate the given payload and throw on failure.
     *
     * @param  ValidatesPayload  $payload  The DTO to validate.
     *
     * @throws FinaValidationException If validation fails.
     */
    public static function validate(ValidatesPayload $payload): void
    {
        $v = Validator::make(
            $payload->toArray(),
            $payload->rules(),
            $payload->messages(),
            $payload->attributes()
        );

        if ($v->fails()) {
            throw new FinaValidationException($v->errors()->toArray());
        }
    }
}
