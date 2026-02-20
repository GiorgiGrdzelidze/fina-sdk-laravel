<?php

declare(strict_types=1);

/**
 * Operation DTO: product line item used in document payloads.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

use Fina\Sdk\Laravel\Contracts\ArrayPayload;

final class ProductLine implements ArrayPayload
{
    public function __construct(
        public readonly int $id,
        public readonly int $subId = 0,
        public readonly float $quantity = 0.0,
        public readonly ?float $price = null,
        public readonly ?int $outId = null // used in returns in some docs; keep optional
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'sub_id' => $this->subId,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'out_id' => $this->outId,
        ], static fn ($v) => $v !== null);
    }
}
