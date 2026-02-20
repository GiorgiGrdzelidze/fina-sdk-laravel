<?php

declare(strict_types=1);

/**
 * Operation DTO: document type reference data with field visibility flags.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

final class DocTypeDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $caption,

        public readonly bool $storeFrom,
        public readonly bool $storeTo,
        public readonly bool $vendor,
        public readonly bool $customer,
        public readonly bool $staff,
        public readonly bool $project,

        public readonly bool $currency,
        public readonly bool $rate,
        public readonly bool $isVat,
        public readonly bool $payType,

        public readonly bool $tType,
        public readonly bool $tPayer,
        public readonly bool $wCost,
        public readonly bool $foreign,

        public readonly bool $prodList,
        public readonly bool $servList,

        public readonly bool $apiSupported,
        public readonly int $cashType,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['id'] ?? 0),
            (string) ($data['caption'] ?? ''),

            (bool) ($data['store_from'] ?? false),
            (bool) ($data['store_to'] ?? false),
            (bool) ($data['vendor'] ?? false),
            (bool) ($data['customer'] ?? false),
            (bool) ($data['staff'] ?? false),
            (bool) ($data['project'] ?? false),

            (bool) ($data['currency'] ?? false),
            (bool) ($data['rate'] ?? false),
            (bool) ($data['is_vat'] ?? false),
            (bool) ($data['pay_type'] ?? false),

            (bool) ($data['t_type'] ?? false),
            (bool) ($data['t_payer'] ?? false),
            (bool) ($data['w_cost'] ?? false),
            (bool) ($data['foreign'] ?? false),

            (bool) ($data['prod_list'] ?? false),
            (bool) ($data['serv_list'] ?? false),

            (bool) ($data['api_supported'] ?? false),
            (int) ($data['cash_type'] ?? 0),
        );
    }
}
