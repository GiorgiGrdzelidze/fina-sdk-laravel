<?php

declare(strict_types=1);

/**
 * Operation DTO: measurement unit reference data.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

final readonly class UnitDto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $fullName,
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
