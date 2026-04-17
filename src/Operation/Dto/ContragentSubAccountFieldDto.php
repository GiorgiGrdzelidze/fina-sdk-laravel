<?php

declare(strict_types=1);

/**
 * Operation DTO: contragent sub-account field definition from getContragentSubAccountFields (v8.0).
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

use Fina\Sdk\Laravel\Operation\CustomersApi;

/**
 * Describes a single user-defined sub-account column (e.g. "State Number").
 *
 * @see CustomersApi::subAccountFields()
 */
final readonly class ContragentSubAccountFieldDto
{
    public function __construct(
        public string $name,
        public string $header,
    ) {}

    /**
     * @param  array<string, mixed>  $data  Raw API response array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (string) ($data['name'] ?? ''),
            (string) ($data['header'] ?? ''),
        );
    }
}
