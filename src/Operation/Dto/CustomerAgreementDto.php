<?php

declare(strict_types=1);

/**
 * Operation DTO: customer agreement from getCustomerAgreements (v8.0).
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

use Fina\Sdk\Laravel\Operation\CustomersApi;

/**
 * Represents a customer price agreement from the FINA API.
 *
 * @see CustomersApi::agreements()
 */
final readonly class CustomerAgreementDto
{
    public function __construct(
        public int $id,
        public int $contragentId,
        public int $priceId,
        public string $name,
        public string $description,
        public float $discount,
        public bool $isActive,
    ) {}

    /**
     * @param  array<string, mixed>  $data  Raw API response array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['id'] ?? 0),
            (int) ($data['contragent_id'] ?? 0),
            (int) ($data['price_id'] ?? 0),
            (string) ($data['name'] ?? ''),
            (string) ($data['description'] ?? ''),
            (float) ($data['discount'] ?? 0),
            (bool) ($data['is_active'] ?? false),
        );
    }
}
