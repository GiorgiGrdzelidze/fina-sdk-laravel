<?php

declare(strict_types=1);

/**
 * Operation DTO: gift card reference data.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

final readonly class GiftCardDto
{
    public function __construct(
        public int $id,
        public int $store,
        public string $code,
        public string $acc,
        public string $issuanceDate,
        public float $amount,
        public float $payAmount,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['id'] ?? 0),
            (int) ($data['store'] ?? 0),
            (string) ($data['code'] ?? ''),
            (string) ($data['acc'] ?? ''),
            (string) ($data['issuance_date'] ?? ''),
            (float) ($data['amount'] ?? 0),
            (float) ($data['pay_amount'] ?? 0),
        );
    }
}
