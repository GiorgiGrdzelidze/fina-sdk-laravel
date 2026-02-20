<?php

declare(strict_types=1);

/**
 * Operation DTO: generic response from any saveDoc* endpoint.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

final readonly class SaveDocResponse
{
    public function __construct(
        public int $id,
        public mixed $ex
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['id'] ?? 0),
            $data['ex'] ?? null
        );
    }
}
