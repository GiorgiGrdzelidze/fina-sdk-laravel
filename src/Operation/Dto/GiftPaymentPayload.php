<?php

declare(strict_types=1);

/**
 * Operation DTO: payload for saveDocGiftPayment (v8.0).
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

use DateTimeInterface;
use Fina\Sdk\Laravel\Contracts\ValidatesPayload;
use Fina\Sdk\Laravel\Operation\DocumentsApi;
use Fina\Sdk\Laravel\Support\FinaDate;

/**
 * Payload for recording a gift card payment via the FINA API.
 *
 * @see DocumentsApi::saveGiftPayment()
 */
final class GiftPaymentPayload implements ValidatesPayload
{
    /**
     * @param  DateTimeInterface  $date  Payment date.
     * @param  int  $cardId  Gift card ID.
     * @param  string  $numPrefix  Document number prefix.
     * @param  int  $num  Document number (0 for auto-assign).
     * @param  string  $purpose  Payment purpose/description.
     * @param  float  $amount  Payment amount (must be > 0).
     * @param  int  $store  Store ID.
     * @param  int  $user  FINA user ID.
     * @param  int  $staff  Staff member ID (0 if not applicable).
     * @param  int  $project  Project ID (0 if not applicable).
     * @param  int  $customer  Customer (contragent) ID.
     * @param  int  $refId  Reference document ID (0 if standalone).
     * @param  bool  $makeEntry  Whether to create an accounting entry.
     */
    public function __construct(
        public readonly DateTimeInterface $date,
        public readonly int $cardId,
        public readonly string $numPrefix,
        public readonly int $num,
        public readonly string $purpose,
        public readonly float $amount,
        public readonly int $store,
        public readonly int $user,
        public readonly int $staff,
        public readonly int $project,
        public readonly int $customer,
        public readonly int $refId,
        public readonly bool $makeEntry,
    ) {}

    public function toArray(): array
    {
        return [
            'date' => FinaDate::toFina($this->date),
            'card_id' => $this->cardId,
            'num_pfx' => $this->numPrefix,
            'num' => $this->num,
            'purpose' => $this->purpose,
            'amount' => $this->amount,
            'store' => $this->store,
            'user' => $this->user,
            'staff' => $this->staff,
            'project' => $this->project,
            'customer' => $this->customer,
            'ref_id' => $this->refId,
            'make_entry' => $this->makeEntry,
        ];
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'string'],
            'card_id' => ['required', 'integer', 'min:1'],
            'num_pfx' => ['nullable', 'string', 'max:20'],
            'num' => ['required', 'integer', 'min:0'],
            'purpose' => ['required', 'string', 'max:750'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'store' => ['required', 'integer', 'min:1'],
            'user' => ['required', 'integer', 'min:1'],
            'staff' => ['required', 'integer', 'min:0'],
            'project' => ['required', 'integer', 'min:0'],
            'customer' => ['required', 'integer', 'min:1'],
            'ref_id' => ['required', 'integer', 'min:0'],
            'make_entry' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [];
    }

    public function attributes(): array
    {
        return [];
    }
}
