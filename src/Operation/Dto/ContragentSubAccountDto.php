<?php

declare(strict_types=1);

/**
 * Operation DTO: contragent sub-account record from getCustomerSubAccounts / getVendorSubAccounts (v8.0).
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

use Fina\Sdk\Laravel\Operation\CustomersApi;
use Fina\Sdk\Laravel\Operation\VendorsApi;

/**
 * Represents a contragent's sub-account data with user-defined field/value pairs.
 *
 * @see CustomersApi::subAccounts()
 * @see VendorsApi::subAccounts()
 */
final readonly class ContragentSubAccountDto
{
    /**
     * @param  array<int, array{field: string, value: string}>  $subAccounts
     */
    public function __construct(
        public int $id,
        public int $contragentId,
        public array $subAccounts,
    ) {}

    /**
     * @param  array<string, mixed>  $data  Raw API response array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['id'] ?? 0),
            (int) ($data['contragent_id'] ?? 0),
            (array) ($data['sub_accounts'] ?? []),
        );
    }
}
