<?php

declare(strict_types=1);

/**
 * Operation DTO: generic response from any saveDoc* endpoint.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

final class SaveDocResponse
{
    public function __construct(
        public readonly int $id,
        public readonly mixed $ex
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['id'] ?? 0),
            $data['ex'] ?? null
        );
    }
}
