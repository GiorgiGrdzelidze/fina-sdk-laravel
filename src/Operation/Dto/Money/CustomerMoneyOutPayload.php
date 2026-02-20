<?php

declare(strict_types=1);

/**
 * Operation DTO: payload for saveDocCustomerMoneyOut.
 */

namespace Fina\Sdk\Laravel\Operation\Dto\Money;

use DateTimeInterface;
use Fina\Sdk\Laravel\Operation\Dto\AddField;

final class CustomerMoneyOutPayload extends AbstractMoneyPayload
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

        public readonly int $customer,

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
        return ['customer' => $this->customer];
    }

    protected function entityRules(): array
    {
        return [
            'customer' => ['required', 'integer', 'min:1'],
        ];
    }
}
