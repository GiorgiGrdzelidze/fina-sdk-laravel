<?php

declare(strict_types=1);

/**
 * Contract for DTOs that support Laravel validation before API submission.
 */

namespace Fina\Sdk\Laravel\Contracts;

/**
 * Interface for payloads that are automatically validated before being sent to the FINA API.
 *
 * Extends {@see ArrayPayload} — the `toArray()` output is used as the validation data.
 */
interface ValidatesPayload extends ArrayPayload
{
    /**
     * Laravel validation rules for this payload.
     *
     * @return array<string, mixed>
     */
    public function rules(): array;

    /**
     * Custom validation error messages (optional, return empty array for defaults).
     *
     * @return array<string, string>
     */
    public function messages(): array;

    /**
     * Custom attribute names for validation errors (optional).
     *
     * @return array<string, string>
     */
    public function attributes(): array;
}
