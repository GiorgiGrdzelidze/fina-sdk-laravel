<?php

declare(strict_types=1);

/**
 * Operation DTO: consumed product line in a production document.
 */

namespace Fina\Sdk\Laravel\Operation\Dto\Production;

final readonly class ProductionConsumedLineDto
{
    public function __construct(
        public int $id,
        public float $selfCost,
        public float $quantity,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['id'] ?? 0),
            (float) ($data['self_cost'] ?? 0),
            (float) ($data['quantity'] ?? 0),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'self_cost' => $this->selfCost,
            'quantity' => $this->quantity,
        ];
    }
}
