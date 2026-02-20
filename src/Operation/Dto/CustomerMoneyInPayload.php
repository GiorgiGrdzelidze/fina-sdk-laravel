<?php

declare(strict_types=1);

/**
 * Operation DTO: payload for saveDocCustomerMoneyIn.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

use DateTimeInterface;
use Fina\Sdk\Laravel\Contracts\ArrayPayload;
use Fina\Sdk\Laravel\Support\FinaDate;

final class CustomerMoneyInPayload implements ArrayPayload
{
    public function __construct(
        public readonly int $id,
        public readonly DateTimeInterface $date,
        public readonly string $numPrefix,
        public readonly int $num,
        public readonly string $purpose,
        public readonly float $amount,
        public readonly string $currency,
        public readonly float $rate,
        public readonly int $store,
        public readonly int $user,
        public readonly int $staff,
        public readonly int $project,
        public readonly int $customer,
        public readonly int $payType,
        public readonly int $payTypeId,
        public readonly int $refId = 0,
        public readonly bool $makeEntry = true,
        public readonly ?array $addFields = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'date' => FinaDate::toFina($this->date),
            'num_pfx' => $this->numPrefix,
            'num' => $this->num,
            'purpose' => $this->purpose,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'rate' => $this->rate,
            'store' => $this->store,
            'user' => $this->user,
            'staff' => $this->staff,
            'project' => $this->project,
            'customer' => $this->customer,
            'pay_type' => $this->payType,
            'pay_type_id' => $this->payTypeId,
            'ref_id' => $this->refId,
            'make_entry' => $this->makeEntry,
            'add_fields' => $this->addFields,
        ], static fn ($v) => $v !== null);
    }
}
