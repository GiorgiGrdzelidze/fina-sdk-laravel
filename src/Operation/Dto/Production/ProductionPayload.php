<?php

declare(strict_types=1);

/**
 * Operation DTO: payload for saveDocProduction.
 */

namespace Fina\Sdk\Laravel\Operation\Dto\Production;

use DateTimeInterface;
use Fina\Sdk\Laravel\Contracts\ArrayPayload;
use Fina\Sdk\Laravel\Contracts\ValidatesPayload;
use Fina\Sdk\Laravel\Support\FinaDate;

final class ProductionPayload implements ArrayPayload, ValidatesPayload
{
    /**
     * @param  array<int,array{field:string,value:string}>  $addFields
     * @param  ProductionMaterialLineDto[]  $materials
     */
    public function __construct(
        public readonly int $id,
        public readonly DateTimeInterface $date,
        public readonly string $numPrefix,
        public readonly int $num,
        public readonly string $purpose,
        public readonly float $amount,
        public readonly int $store,
        public readonly int $user,
        public readonly bool $makeEntry,
        public readonly int $productionType,
        public readonly array $addFields = [],
        public readonly array $materials = [],
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'date' => FinaDate::toFina($this->date),
            'num_pfx' => $this->numPrefix,
            'num' => $this->num,
            'purpose' => $this->purpose,
            'amount' => $this->amount,
            'store' => $this->store,
            'user' => $this->user,
            'make_entry' => $this->makeEntry,
            'production_type' => $this->productionType,
            'add_fields' => array_map(
                fn (array $x) => ['field' => (string) $x['field'], 'value' => (string) ($x['value'] ?? '')],
                $this->addFields
            ),
            'materials' => array_map(fn (ProductionMaterialLineDto $m) => $m->toArray(), $this->materials),
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
            'amount' => ['required', 'numeric', 'gte:0'],
            'store' => ['required', 'integer', 'min:1'],
            'user' => ['required', 'integer', 'min:1'],
            'make_entry' => ['required', 'boolean'],
            'production_type' => ['required', 'integer', 'min:0'],

            'add_fields' => ['present', 'array'],
            'add_fields.*.field' => ['required_with:add_fields', 'string'],
            'add_fields.*.value' => ['nullable', 'string'],

            'materials' => ['present', 'array'],
            'materials.*.id' => ['required', 'integer', 'min:1'],
            'materials.*.self_cost' => ['required', 'numeric', 'gte:0'],
            'materials.*.quantity' => ['required', 'numeric', 'gt:0'],

            'materials.*.consumeds' => ['present', 'array'],
            'materials.*.consumeds.*.id' => ['required', 'integer', 'min:1'],
            'materials.*.consumeds.*.self_cost' => ['required', 'numeric', 'gte:0'],
            'materials.*.consumeds.*.quantity' => ['required', 'numeric', 'gt:0'],

            'materials.*.expenses' => ['present', 'array'],
            'materials.*.expenses.*.type' => ['required', 'integer', 'min:0'],
            'materials.*.expenses.*.amount' => ['required', 'numeric', 'gte:0'],
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
