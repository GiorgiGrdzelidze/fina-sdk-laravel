<?php

declare(strict_types=1);

/**
 * Operation DTO: payload for saveDocProductIn.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

use DateTimeInterface;
use Fina\Sdk\Laravel\Contracts\ValidatesPayload;
use Fina\Sdk\Laravel\Support\FinaDate;

final class ProductInPayload implements ValidatesPayload
{
    /**
     * @param  ProductLine[]  $products
     * @param  AddField[]  $addFields
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
        public readonly int $vendor,
        public readonly bool $isVat,
        public readonly bool $makeEntry,
        public readonly ?string $wNum = null,
        public readonly ?string $iNum = null,
        /** @var AddField[] */
        public readonly array $addFields = [],
        /** @var ProductLine[] */
        public readonly array $products = [],
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
            'vendor' => $this->vendor,
            'is_vat' => $this->isVat,
            'make_entry' => $this->makeEntry,
            'w_num' => $this->wNum,
            'i_num' => $this->iNum,
            'add_fields' => array_map(fn (AddField $f) => $f->toArray(), $this->addFields),
            'products' => array_map(fn (ProductLine $p) => $p->toArray(), $this->products),
        ], static fn ($v) => $v !== null);
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'min:0'],
            'date' => ['required', 'string'],
            'currency' => ['required', 'string', 'max:10'],
            'rate' => ['required', 'numeric'],
            'store' => ['required', 'integer', 'min:1'],
            'user' => ['required', 'integer', 'min:1'],
            'vendor' => ['required', 'integer', 'min:1'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.id' => ['required', 'integer', 'min:1'],
            'products.*.quantity' => ['required', 'numeric', 'gt:0'],
            'products.*.price' => ['required', 'numeric', 'gte:0'],
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
