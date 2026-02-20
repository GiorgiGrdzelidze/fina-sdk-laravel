<?php

declare(strict_types=1);

/**
 * Operation DTO: response from saveDocBonusOperation.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

final readonly class BonusOperationResponse
{
    public function __construct(
        public bool $res,
        public mixed $ex
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            (bool) ($data['res'] ?? false),
            $data['ex'] ?? null
        );
    }
}
