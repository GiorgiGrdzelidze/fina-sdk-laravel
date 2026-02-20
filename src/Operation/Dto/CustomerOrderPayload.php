<?php

declare(strict_types=1);

/**
 * Operation DTO: payload for saveDocCustomerOrder.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

use DateTimeInterface;
use Fina\Sdk\Laravel\Contracts\ArrayPayload;
use Fina\Sdk\Laravel\Support\FinaDate;

final class CustomerOrderPayload implements ArrayPayload
{
    /**
     * Keep it close to FINA body example (optional fields can be null).
     */
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
        public readonly bool $isVat,
        public readonly int $payType,
        /** @var CustomerOrderProduct[] */
        public readonly array $products,

        // optional fields:
        public readonly ?string $trStart = null,
        public readonly ?string $trEnd = null,
        public readonly ?int $invoiceNum = null,
        public readonly ?int $invoiceBank = null,
        public readonly ?DateTimeInterface $payDate = null,
        public readonly ?DateTimeInterface $deliveryDate = null,
        public readonly ?DateTimeInterface $reservedUntil = null,
        public readonly ?bool $reserved = null,
        public readonly ?bool $actived = null,
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
            'is_vat' => $this->isVat,
            'pay_type' => $this->payType,

            'tr_start' => $this->trStart,
            'tr_end' => $this->trEnd,
            'invoice_num' => $this->invoiceNum,
            'invoice_bank' => $this->invoiceBank,
            'pay_date' => $this->payDate ? FinaDate::toFina($this->payDate) : null,
            'delivery_date' => $this->deliveryDate ? FinaDate::toFina($this->deliveryDate) : null,
            'reserved_until' => $this->reservedUntil ? FinaDate::toFina($this->reservedUntil) : null,
            'reserved' => $this->reserved,
            'actived' => $this->actived,
            'add_fields' => $this->addFields,

            'products' => array_map(fn (CustomerOrderProduct $p) => $p->toArray(), $this->products),
        ], static fn ($v) => $v !== null);
    }
}
