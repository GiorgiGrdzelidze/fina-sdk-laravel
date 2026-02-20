<?php

declare(strict_types=1);

/**
 * Reporting DTO: typed response wrapper for the Products In/Return Report.
 */

namespace Fina\Sdk\Laravel\Reporting\Dto;

/**
 * Typed response for getProductsInReturnReport.
 *
 * Wraps the raw API response into a collection of {@see ProductsInReturnReportRowDto}
 * rows plus the optional FINA `ex` error field.
 */
final class ProductsInReturnReportResponseDto
{
    /**
     * @param  ProductsInReturnReportRowDto[]  $reports  Typed report rows.
     * @param  string|null  $ex  FINA error field (null = no error).
     */
    public function __construct(
        public readonly array $reports,
        public readonly ?string $ex,
    ) {}

    /**
     * Build from raw FINA API response array.
     *
     * @param  array{reports?: list<array<string,mixed>>, ex?: string|null}  $data
     */
    public static function fromArray(array $data): self
    {
        $rows = array_map(
            static fn ($r) => ProductsInReturnReportRowDto::fromArray((array) $r),
            (array) ($data['reports'] ?? [])
        );

        return new self(
            reports: $rows,
            ex: isset($data['ex']) ? (is_null($data['ex']) ? null : (string) $data['ex']) : null,
        );
    }

    /**
     * Serialize back to a plain array.
     *
     * @return array{reports: list<array<string,mixed>>, ex: string|null}
     */
    public function toArray(): array
    {
        return [
            'reports' => array_map(static fn (ProductsInReturnReportRowDto $r) => $r->toArray(), $this->reports),
            'ex' => $this->ex,
        ];
    }
}
