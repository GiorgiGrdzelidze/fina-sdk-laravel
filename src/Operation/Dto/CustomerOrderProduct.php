<?php

declare(strict_types=1);

/**
 * Operation DTO: product line item for customer order payloads.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

use Fina\Sdk\Laravel\Contracts\ArrayPayload;

final class CustomerOrderProduct implements ArrayPayload
{
    public function __construct(
        public readonly int $id,
        public readonly int $subId,
        public readonly float $quantity,
        public readonly float $price
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'sub_id' => $this->subId,
            'quantity' => $this->quantity,
            'price' => $this->price,
        ];
    }
}
