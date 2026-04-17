<?php

declare(strict_types=1);

/**
 * Operation DTO: payload for saveDocProduction (v8.0).
 */

namespace Fina\Sdk\Laravel\Operation\Dto\Production;

use DateTimeInterface;
use Fina\Sdk\Laravel\Contracts\ValidatesPayload;
use Fina\Sdk\Laravel\Operation\DocumentsApi;
use Fina\Sdk\Laravel\Operation\Dto\AddField;
use Fina\Sdk\Laravel\Support\FinaDate;

/**
 * Payload for creating/updating a production document with product tree structure.
 *
 * Supports production types: 0=production, 1=disassembly, 2=part addition/repair, 3=part separation.
 *
 * @see DocumentsApi::saveProduction()
 */
final class SaveProductionPayload implements ValidatesPayload
{
    /**
     * @param  int  $type  Production type (0=production, 1=disassembly, 2=part addition/repair, 3=part separation)
     * @param  AddField[]  $addFields
     * @param  ProductionProductLine[]  $products
     */
    public function __construct(
        public readonly int $id,
        public readonly DateTimeInterface $date,
        public readonly string $numPrefix,
        public readonly int $num,
        public readonly string $purpose,
        public readonly int $type,
        public readonly int $store,
        public readonly int $user,
        public readonly int $staff,
        public readonly bool $makeEntry,
        public readonly array $addFields = [],
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
            'type' => $this->type,
            'store' => $this->store,
            'user' => $this->user,
            'staff' => $this->staff,
            'make_entry' => $this->makeEntry,
            'add_fields' => array_map(fn (AddField $f) => $f->toArray(), $this->addFields),
            'products' => array_map(fn (ProductionProductLine $p) => $p->toArray(), $this->products),
        ], static fn ($v) => $v !== null);
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'min:0'],
            'date' => ['required', 'string'],
            'num_pfx' => ['nullable', 'string', 'max:20'],
            'num' => ['required', 'integer', 'min:0'],
            'purpose' => ['required', 'string', 'max:750'],
            'type' => ['required', 'integer', 'min:0'],
            'store' => ['required', 'integer', 'min:1'],
            'user' => ['required', 'integer', 'min:1'],
            'staff' => ['required', 'integer', 'min:0'],
            'make_entry' => ['required', 'boolean'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.id' => ['required', 'integer', 'min:1'],
            'products.*.quantity' => ['required', 'numeric', 'gt:0'],
        ];
    }

    public function messages(): array
    {
        return [];
    }

    public function attributes(): array
    {
        return [
            'products.*.id' => 'product id',
            'products.*.quantity' => 'product quantity',
        ];
    }
}
