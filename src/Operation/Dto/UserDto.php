<?php

declare(strict_types=1);

/**
 * Operation DTO: FINA user reference data.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

final class UserDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $type,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['id'] ?? 0),
            (string) ($data['name'] ?? ''),
            (int) ($data['type'] ?? 0),
        );
    }
}
