<?php

declare(strict_types=1);

/**
 * Reporting DTO: row from the Products In/Return Report response.
 */

namespace Fina\Sdk\Laravel\Reporting\Dto;

/**
 * getProductsInReturnReport -> reports[] row.
 *
 * Fields can vary; we keep `$raw` for forward-compatibility.
 */
final class ProductsInReturnReportRowDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $barcode,
        public readonly float $inQuantity,
        public readonly float $returnQuantity,
        public readonly float $diffQuantity,
        public readonly array $raw = [],
    ) {}

    public static function fromArray(array $row): self
    {
        return new self(
            id: (int) ($row['id'] ?? ($row['product_id'] ?? 0)),
            name: (string) ($row['name'] ?? ($row['product_name'] ?? '')),
            barcode: (string) ($row['barcode'] ?? ''),
            inQuantity: (float) ($row['in_qty'] ?? ($row['in_quantity'] ?? 0.0)),
            returnQuantity: (float) ($row['ret_qty'] ?? ($row['return_quantity'] ?? 0.0)),
            diffQuantity: (float) ($row['diff_qty'] ?? ($row['diff_quantity'] ?? 0.0)),
            raw: $row,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'barcode' => $this->barcode,
            'in_qty' => $this->inQuantity,
            'ret_qty' => $this->returnQuantity,
            'diff_qty' => $this->diffQuantity,
            'raw' => $this->raw,
        ];
    }
}
