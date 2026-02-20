<?php

declare(strict_types=1);

/**
 * Operation DTO: additional field key-value pair for document payloads.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

use Fina\Sdk\Laravel\Contracts\ArrayPayload;

final class AddField implements ArrayPayload
{
    public function __construct(
        public readonly string $field,
        public readonly string $value,
    ) {}

    public function toArray(): array
    {
        return [
            'field' => $this->field,
            'value' => $this->value,
        ];
    }
}
