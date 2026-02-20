<?php

declare(strict_types=1);

/**
 * Operation DTO: product line item for cafe order payloads.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

use Fina\Sdk\Laravel\Contracts\ArrayPayload;

final class CafeOrderProductLine implements ArrayPayload
{
    public function __construct(
        public readonly int $id,
        public readonly float $quantity,
        public readonly float $price
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'price' => $this->price,
        ];
    }
}
