<?php

declare(strict_types=1);

/**
 * Operation DTO: measurement unit reference data.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

final class UnitDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $fullName,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['id'] ?? 0),
            (string) ($data['name'] ?? ''),
            (string) ($data['full_name'] ?? ''),
        );
    }
}
