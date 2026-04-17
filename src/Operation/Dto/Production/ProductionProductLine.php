<?php

declare(strict_types=1);

/**
 * Operation DTO: product line for saveDocProduction (v8.0).
 */

namespace Fina\Sdk\Laravel\Operation\Dto\Production;

use Fina\Sdk\Laravel\Contracts\ArrayPayload;

/**
 * Represents a produced product with its child (consumed) materials.
 *
 * Used within {@see SaveProductionPayload::$products}.
 */
final class ProductionProductLine implements ArrayPayload
{
    /**
     * @param  ProductionChildLine[]  $childProducts
     */
    public function __construct(
        public readonly int $id,
        public readonly int $subId = 0,
        public readonly float $quantity = 0.0,
        public readonly array $childProducts = [],
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'sub_id' => $this->subId,
            'quantity' => $this->quantity,
            'child_products' => array_map(
                fn (ProductionChildLine $c) => $c->toArray(),
                $this->childProducts
            ),
        ];
    }
}
