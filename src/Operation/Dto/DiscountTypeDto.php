<?php

declare(strict_types=1);

/**
 * Operation DTO: discount type reference data.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

final class DiscountTypeDto
{
    public function __construct(
        public readonly int $id,
        public readonly float $discountPercent,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['id'] ?? 0),
            (float) ($data['discount_percent'] ?? 0),
        );
    }
}
