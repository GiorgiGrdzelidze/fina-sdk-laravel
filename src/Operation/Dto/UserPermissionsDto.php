<?php

declare(strict_types=1);

/**
 * Operation DTO: FINA user permissions and defaults.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

final class UserPermissionsDto
{
    public function __construct(
        public readonly int $defaultStore,
        public readonly int $defaultCash,
        public readonly int $defaultPrice,
        public readonly float $maxDiscount,
        public readonly float $maxMoneyIn,
        public readonly int $fiscalPrint,
        /** @var int[] */
        public readonly array $stores,
        /** @var int[] */
        public readonly array $cashes,
        /** @var int[] */
        public readonly array $priceTypes,
        /** @var int[] */
        public readonly array $users,
        public readonly mixed $raw = null, // future-proof: keep original payload if needed
    ) {}

    public static function fromArray(array $data): self
    {
        // PDF response is: { permissions: {...}, ex: null }
        $p = (array) ($data['permissions'] ?? []);

        return new self(
            (int) ($p['default_store'] ?? 0),
            (int) ($p['default_cash'] ?? 0),
            (int) ($p['default_price'] ?? 0),
            (float) ($p['max_discount'] ?? 0),
            (float) ($p['max_moneyin'] ?? 0),
            (int) ($p['fiscal_print'] ?? 0),
            array_map('intval', (array) ($p['stores'] ?? [])),
            array_map('intval', (array) ($p['cashes'] ?? [])),
            array_map('intval', (array) ($p['price_types'] ?? [])),
            array_map('intval', (array) ($p['users'] ?? [])),
            $data,
        );
    }
}
