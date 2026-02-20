<?php

declare(strict_types=1);

/**
 * Operation DTO: typed response for getDocProduction.
 */

namespace Fina\Sdk\Laravel\Operation\Dto\Production;

final readonly class ProductionDocDto
{
    /**
     * @param  array<int,array{field:string,value:string}>  $addFields
     * @param  ProductionMaterialLineDto[]  $materials
     */
    public function __construct(
        public int $id,
        public string $date,
        public string $numPrefix,
        public int $num,
        public string $purpose,
        public float $amount,
        public int $store,
        public int $user,
        public bool $makeEntry,
        public int $productionType,
        public array $addFields = [],
        public array $materials = [],
    ) {}

    public static function fromArray(array $data): self
    {
        $addFields = array_map(
            fn ($x) => [
                'field' => (string) (($x['field'] ?? '') ?: ''),
                'value' => (string) (($x['value'] ?? '') ?: ''),
            ],
            (array) ($data['add_fields'] ?? [])
        );

        $materials = array_map(
            fn ($x) => ProductionMaterialLineDto::fromArray((array) $x),
            (array) ($data['materials'] ?? [])
        );

        return new self(
            (int) ($data['id'] ?? 0),
            (string) ($data['date'] ?? ''),
            (string) ($data['num_pfx'] ?? ''),
            (int) ($data['num'] ?? 0),
            (string) ($data['purpose'] ?? ''),
            (float) ($data['amount'] ?? 0),
            (int) ($data['store'] ?? 0),
            (int) ($data['user'] ?? 0),
            (bool) ($data['make_entry'] ?? false),
            (int) ($data['production_type'] ?? 0),
            $addFields,
            $materials,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'num_pfx' => $this->numPrefix,
            'num' => $this->num,
            'purpose' => $this->purpose,
            'amount' => $this->amount,
            'store' => $this->store,
            'user' => $this->user,
            'make_entry' => $this->makeEntry,
            'production_type' => $this->productionType,
            'add_fields' => $this->addFields,
            'materials' => array_map(fn (ProductionMaterialLineDto $m) => $m->toArray(), $this->materials),
        ];
    }
}
