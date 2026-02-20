<?php

declare(strict_types=1);

/**
 * Operation DTO: bank account reference data.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

final class BankAccountDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $code,
        public readonly string $name,
        public readonly string $account,
        public readonly string $currency,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['id'] ?? 0),
            (string) ($data['code'] ?? ''),
            (string) ($data['name'] ?? ''),
            (string) ($data['account'] ?? ''),
            (string) ($data['currency'] ?? ''),
        );
    }
}
