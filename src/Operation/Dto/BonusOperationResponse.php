<?php

declare(strict_types=1);

/**
 * Operation DTO: response from saveDocBonusOperation.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

final readonly class BonusOperationResponse
{
    /**
     * @param  bool  $res  Whether the operation succeeded.
     * @param  mixed  $ex  Error field (null on success).
     */
    public function __construct(
        public bool $res,
        public mixed $ex
    ) {}

    /**
     * @param  array<string, mixed>  $data  Raw API response array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (bool) ($data['res'] ?? false),
            $data['ex'] ?? null
        );
    }
}
