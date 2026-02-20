<?php

declare(strict_types=1);

/**
 * Operation DTO: payload for saveDocBonusOperation.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

use Fina\Sdk\Laravel\Contracts\ValidatesPayload;

final class BonusOperationPayload implements ValidatesPayload
{
    public function __construct(
        public readonly int $cardId,
        public readonly int $refId,
        /** 1 = accumulate, -1 = spend */
        public readonly int $coeff,
        /** calculated amount in money */
        public readonly float $amount,
    ) {}

    public function toArray(): array
    {
        return [
            'card_id' => $this->cardId,
            'ref_id' => $this->refId,
            'coeff' => $this->coeff,
            'amount' => $this->amount,
        ];
    }

    public function rules(): array
    {
        return [
            'card_id' => ['required', 'integer', 'min:1'],
            'ref_id' => ['required', 'integer', 'min:0'],
            'coeff' => ['required', 'integer', 'in:1,-1'],
            'amount' => ['required', 'numeric', 'gt:0'],
        ];
    }

    public function messages(): array
    {
        return [];
    }

    public function attributes(): array
    {
        return [
            'card_id' => 'loyalty card id',
            'ref_id' => 'reference operation id',
        ];
    }
}
