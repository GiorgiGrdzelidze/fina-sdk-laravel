<?php

declare(strict_types=1);

/**
 * Contract for DTOs that can be serialized to a FINA API request array.
 */

namespace Fina\Sdk\Laravel\Contracts;

/**
 * Interface for any DTO that can produce a plain array for the FINA API.
 */
interface ArrayPayload
{
    /**
     * Serialize this payload to an array suitable for a FINA API request body.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
