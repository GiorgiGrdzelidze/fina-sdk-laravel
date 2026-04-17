<?php

declare(strict_types=1);

/**
 * Operation DTO: payload for saveDocBonusCard (v8.0).
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

use DateTimeInterface;
use Fina\Sdk\Laravel\Contracts\ValidatesPayload;
use Fina\Sdk\Laravel\Operation\DocumentsApi;
use Fina\Sdk\Laravel\Support\FinaDate;

/**
 * Payload for issuing or updating a bonus/discount card via the FINA API.
 *
 * @see DocumentsApi::saveBonusCard()
 */
final class BonusCardPayload implements ValidatesPayload
{
    /**
     * @param  int  $id  Document ID (0 for new).
     * @param  DateTimeInterface  $date  Document date.
     * @param  string  $numPrefix  Document number prefix.
     * @param  int  $num  Document number (0 for auto-assign).
     * @param  string  $purpose  Document purpose/description.
     * @param  int  $customer  Customer (contragent) ID.
     * @param  int  $store  Store ID.
     * @param  int  $user  FINA user ID.
     * @param  string  $cardCode  Bonus card code.
     * @param  string  $personCode  Card holder's personal identification code.
     * @param  string  $personName  Card holder's full name.
     * @param  string  $personAddress  Card holder's address.
     * @param  string  $personTel  Card holder's phone number.
     * @param  bool  $status  Card active status.
     */
    public function __construct(
        public readonly int $id,
        public readonly DateTimeInterface $date,
        public readonly string $numPrefix,
        public readonly int $num,
        public readonly string $purpose,
        public readonly int $customer,
        public readonly int $store,
        public readonly int $user,
        public readonly string $cardCode,
        public readonly string $personCode,
        public readonly string $personName,
        public readonly string $personAddress,
        public readonly string $personTel,
        public readonly bool $status,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'date' => FinaDate::toFina($this->date),
            'num_pfx' => $this->numPrefix,
            'num' => $this->num,
            'purpose' => $this->purpose,
            'customer' => $this->customer,
            'store' => $this->store,
            'user' => $this->user,
            'card_code' => $this->cardCode,
            'person_code' => $this->personCode,
            'person_name' => $this->personName,
            'person_address' => $this->personAddress,
            'person_tel' => $this->personTel,
            'status' => $this->status,
        ];
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'min:0'],
            'date' => ['required', 'string'],
            'num_pfx' => ['nullable', 'string', 'max:20'],
            'num' => ['required', 'integer', 'min:0'],
            'purpose' => ['required', 'string', 'max:750'],
            'customer' => ['required', 'integer', 'min:1'],
            'store' => ['required', 'integer', 'min:1'],
            'user' => ['required', 'integer', 'min:1'],
            'card_code' => ['required', 'string'],
            'person_code' => ['required', 'string'],
            'person_name' => ['required', 'string'],
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
