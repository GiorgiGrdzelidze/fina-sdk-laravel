<?php

declare(strict_types=1);

/**
 * Operation DTO: expense line in a production document.
 */

namespace Fina\Sdk\Laravel\Operation\Dto\Production;

final class ProductionExpenseLineDto
{
    public function __construct(
        public readonly int $type,
        public readonly float $amount,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['type'] ?? 0),
            (float) ($data['amount'] ?? 0),
        );
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'amount' => $this->amount,
        ];
    }
}
