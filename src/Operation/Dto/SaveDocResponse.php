<?php

declare(strict_types=1);

/**
 * Operation DTO: generic response from any saveDoc* endpoint.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

final readonly class SaveDocResponse
{
    /**
     * @param  int  $id  The saved document's ID (0 if failed).
     * @param  mixed  $ex  Error field (null on success).
     */
    public function __construct(
        public int $id,
        public mixed $ex
    ) {}

    /**
     * @param  array<string, mixed>  $data  Raw API response array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['id'] ?? 0),
            $data['ex'] ?? null
        );
    }
}
