<?php

declare(strict_types=1);

/**
 * Operation DTO: bank account reference data.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

final readonly class BankAccountDto
{
    public function __construct(
        public int $id,
        public string $code,
        public string $name,
        public string $account,
        public string $currency,
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
