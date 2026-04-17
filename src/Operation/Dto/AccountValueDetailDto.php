<?php

declare(strict_types=1);

/**
 * Operation DTO: account value detail from getAccountValueDetails (v8.0).
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

use Fina\Sdk\Laravel\Operation\ReferenceApi;

/**
 * Represents a single entity's debit/credit values for a given account.
 *
 * @see ReferenceApi::accountValueDetails()
 */
final readonly class AccountValueDetailDto
{
    public function __construct(
        public int $id,
        public float $debitVal,
        public float $creditVal,
    ) {}

    /**
     * @param  array<string, mixed>  $data  Raw API response array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['id'] ?? 0),
            (float) ($data['debit_val'] ?? 0),
            (float) ($data['credit_val'] ?? 0),
        );
    }
}
