<?php

declare(strict_types=1);

/**
 * Operation DTO: staff additional field key-value pair.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

final class StaffAdditionalFieldDto
{
    public function __construct(
        public readonly string $field,
        public readonly string $value,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            (string) ($data['field'] ?? ''),
            (string) ($data['value'] ?? ''),
        );
    }
}
