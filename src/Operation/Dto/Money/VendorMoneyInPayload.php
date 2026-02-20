<?php

declare(strict_types=1);

/**
 * Operation DTO: payload for saveDocVendorMoneyIn (also base for Out/Return).
 */

namespace Fina\Sdk\Laravel\Operation\Dto\Money;

use DateTimeInterface;
use Fina\Sdk\Laravel\Operation\Dto\AddField;

class VendorMoneyInPayload extends AbstractMoneyPayload
{
    /**
     * @param  AddField[]  $addFields
     */
    public function __construct(
        int $id,
        DateTimeInterface $date,
        string $numPrefix,
        int $num,
        string $purpose,

        float $amount,
        string $currency,
        float $rate,

        int $store,
        int $user,
        int $staff,
        int $project,

        public readonly int $vendor,

        int $payType,
        int $payTypeId,
        int $refId = 0,
        bool $makeEntry = true,

        array $addFields = [],
    ) {
        parent::__construct(
            id: $id,
            date: $date,
            numPrefix: $numPrefix,
            num: $num,
            purpose: $purpose,
            amount: $amount,
            currency: $currency,
            rate: $rate,
            store: $store,
            user: $user,
            staff: $staff,
            project: $project,
            payType: $payType,
            payTypeId: $payTypeId,
            refId: $refId,
            makeEntry: $makeEntry,
            addFields: $addFields,
        );
    }

    protected function entityPart(): array
    {
        return ['vendor' => $this->vendor];
    }

    protected function entityRules(): array
    {
        return [
            'vendor' => ['required', 'integer', 'min:1'],
        ];
    }
}
