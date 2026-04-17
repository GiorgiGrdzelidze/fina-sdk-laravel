<?php

declare(strict_types=1);

/**
 * Reporting DTO: typed response wrapper for the Cafe Order Detailed Report (v8.0).
 */

namespace Fina\Sdk\Laravel\Reporting\Dto;

use Fina\Sdk\Laravel\Reporting\ReportingApi;

/**
 * Wraps the getCafeOrderDetailedReport response into typed row DTOs.
 *
 * @see ReportingApi::cafeOrderDetailedReportTyped()
 */
final class CafeOrderDetailedReportResponseDto
{
    /**
     * @param  CafeOrderDetailedReportRowDto[]  $reports
     */
    public function __construct(
        public readonly array $reports,
        public readonly ?string $ex,
    ) {}

    /**
     * @param  array<string, mixed>  $data  Raw API response array.
     */
    public static function fromArray(array $data): self
    {
        $rows = array_map(
            static fn ($r) => CafeOrderDetailedReportRowDto::fromArray((array) $r),
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
            'reports' => array_map(static fn (CafeOrderDetailedReportRowDto $r) => $r->toArray(), $this->reports),
            'ex' => $this->ex,
        ];
    }
}
