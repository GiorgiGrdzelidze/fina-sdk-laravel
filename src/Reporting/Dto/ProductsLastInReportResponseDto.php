<?php

declare(strict_types=1);

/**
 * Reporting DTO: typed response wrapper for the Products Last-In Report.
 */

namespace Fina\Sdk\Laravel\Reporting\Dto;

final class ProductsLastInReportResponseDto
{
    /**
     * @param  ProductsLastInReportRowDto[]  $reports
     */
    public function __construct(
        public readonly array $reports,
        public readonly ?string $ex,
    ) {}

    public static function fromArray(array $data): self
    {
        $rows = array_map(
            static fn ($r) => ProductsLastInReportRowDto::fromArray((array) $r),
            (array) ($data['reports'] ?? [])
        );

        return new self(
            reports: $rows,
            ex: isset($data['ex']) ? (is_null($data['ex']) ? null : (string) $data['ex']) : null,
        );
    }

    public function toArray(): array
    {
        return [
            'reports' => array_map(static fn (ProductsLastInReportRowDto $r) => $r->toArray(), $this->reports),
            'ex' => $this->ex,
        ];
    }
}
