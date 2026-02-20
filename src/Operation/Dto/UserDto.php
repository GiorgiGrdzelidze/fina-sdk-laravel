<?php

declare(strict_types=1);

/**
 * Operation DTO: FINA user reference data.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

final readonly class UserDto
{
    public function __construct(
        public int $id,
        public string $name,
        public int $type,
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
