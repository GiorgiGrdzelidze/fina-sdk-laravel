<?php

declare(strict_types=1);

/**
 * Operation DTO: service line item used in document payloads.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

use Fina\Sdk\Laravel\Contracts\ArrayPayload;

final class ServiceLine implements ArrayPayload
{
    public function __construct(
        public readonly int $id,
        public readonly float $quantity = 0.0,
        public readonly float $price = 0.0,
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
