<?php

declare(strict_types=1);

/**
 * Operation DTO: FINA user permissions and defaults.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

final readonly class UserPermissionsDto
{
    /**
     * @param  int[]  $stores
     * @param  int[]  $cashes
     * @param  int[]  $priceTypes
     * @param  int[]  $users
     */
    public function __construct(
        public int $defaultStore,
        public int $defaultCash,
        public int $defaultPrice,
        public float $maxDiscount,
        public float $maxMoneyIn,
        public int $fiscalPrint,
        public array $stores,
        public array $cashes,
        public array $priceTypes,
        public array $users,
        public mixed $raw = null,
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
