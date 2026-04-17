<?php

declare(strict_types=1);

/**
 * Operation DTO: child product line within a production product (v8.0).
 */

namespace Fina\Sdk\Laravel\Operation\Dto\Production;

use Fina\Sdk\Laravel\Contracts\ArrayPayload;

/**
 * Represents a raw material / child product consumed during production.
 *
 * Used as a nested item within {@see ProductionProductLine::$childProducts}.
 */
final class ProductionChildLine implements ArrayPayload
{
    /**
     * @param  int  $id  Product ID.
     * @param  int  $subId  Sub-product ID (0 if none).
     * @param  float  $quantity  Quantity consumed.
     * @param  float  $price  Unit price (0 for auto-calculation).
     */
    public function __construct(
        public readonly int $id,
        public readonly int $subId = 0,
        public readonly float $quantity = 0.0,
        public readonly float $price = 0.0,
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
