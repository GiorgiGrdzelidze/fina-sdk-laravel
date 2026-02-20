<?php

declare(strict_types=1);

/**
 * Operation DTO: staff group reference data.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

final class StaffGroupDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $parentId,
        public readonly string $path,
        public readonly string $name,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['id'] ?? 0),
            (int) ($data['parent_id'] ?? 0),
            (string) ($data['path'] ?? ''),
            (string) ($data['name'] ?? ''),
        );
    }
}
