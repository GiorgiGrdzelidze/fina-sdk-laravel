<?php

declare(strict_types=1);

/**
 * Operation DTO: document type reference data from FINA API.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

final readonly class DocTypeDto
{
    public function __construct(
        public int $type,
        public string $name,
        public bool $apiSupported,
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
