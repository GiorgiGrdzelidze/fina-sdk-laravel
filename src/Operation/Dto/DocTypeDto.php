<?php

declare(strict_types=1);

/**
 * Operation DTO: document type reference data from FINA API.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

final class DocTypeDto
{
    public function __construct(
        public readonly int $type,
        public readonly string $name,
        public readonly bool $apiSupported,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['type'] ?? 0),
            (string) ($data['name'] ?? ''),
            (bool) ($data['api_supported'] ?? false),
        );
    }
}
