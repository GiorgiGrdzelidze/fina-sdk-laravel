<?php

declare(strict_types=1);

/**
 * Operation DTO: detailed gift card info from getGiftCardInfoByCode (v8.0).
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

use Fina\Sdk\Laravel\Operation\LoyaltyApi;

/**
 * Represents detailed gift card information retrieved by card code.
 *
 * @see LoyaltyApi::giftCardInfoByCode()
 */
final readonly class GiftCardInfoDto
{
    public function __construct(
        public int $id,
        public int $store,
        public string $code,
        public string $acc,
        public string $issuanceDate,
        public float $amount,
        public float $payAmount,
        public float $restAmount,
    ) {}

    /**
     * @param  array<string, mixed>  $data  Raw API response array.
     */
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
            (float) ($data['rest_amount'] ?? 0),
        );
    }
}
