<?php

declare(strict_types=1);

/**
 * Reporting DTO: row from the Products Last-In Report response.
 */

namespace Fina\Sdk\Laravel\Reporting\Dto;

/**
 * getProductsLastInReport -> reports[] row.
 *
 * Fields can vary; we keep `$raw` for forward-compatibility.
 */
final class ProductsLastInReportRowDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $barcode,
        public readonly string $date,
        public readonly float $quantity,
        public readonly float $price,
        public readonly string $currency,
        public readonly array $raw = [],
    ) {}

    public static function fromArray(array $row): self
    {
        return new self(
            id: (int) ($row['id'] ?? ($row['product_id'] ?? 0)),
            name: (string) ($row['name'] ?? ($row['product_name'] ?? '')),
            barcode: (string) ($row['barcode'] ?? ''),
            date: (string) ($row['date'] ?? ''),
            quantity: (float) ($row['quantity'] ?? 0.0),
            price: (float) ($row['price'] ?? 0.0),
            currency: (string) ($row['currency'] ?? ''),
            raw: $row,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'barcode' => $this->barcode,
            'date' => $this->date,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'currency' => $this->currency,
            'raw' => $this->raw,
        ];
    }
}
