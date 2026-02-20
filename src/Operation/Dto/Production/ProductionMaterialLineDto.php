<?php

declare(strict_types=1);

/**
 * Operation DTO: production material line with consumed items and expenses.
 */

namespace Fina\Sdk\Laravel\Operation\Dto\Production;

final readonly class ProductionMaterialLineDto
{
    /**
     * @param  ProductionConsumedLineDto[]  $consumeds
     * @param  ProductionExpenseLineDto[]  $expenses
     */
    public function __construct(
        public int $id,
        public float $selfCost,
        public float $quantity,
        public array $consumeds = [],
        public array $expenses = [],
    ) {}

    public static function fromArray(array $data): self
    {
        $consumeds = array_map(
            fn ($x) => ProductionConsumedLineDto::fromArray((array) $x),
            (array) ($data['consumeds'] ?? [])
        );

        $expenses = array_map(
            fn ($x) => ProductionExpenseLineDto::fromArray((array) $x),
            (array) ($data['expenses'] ?? [])
        );

        return new self(
            (int) ($data['id'] ?? 0),
            (float) ($data['self_cost'] ?? 0),
            (float) ($data['quantity'] ?? 0),
            $consumeds,
            $expenses,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'self_cost' => $this->selfCost,
            'quantity' => $this->quantity,
            'consumeds' => array_map(fn (ProductionConsumedLineDto $c) => $c->toArray(), $this->consumeds),
            'expenses' => array_map(fn (ProductionExpenseLineDto $e) => $e->toArray(), $this->expenses),
        ];
    }
}
