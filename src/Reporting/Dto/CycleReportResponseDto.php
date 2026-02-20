<?php

declare(strict_types=1);

/**
 * Reporting DTO: typed response wrapper for the Cycle Report.
 */

namespace Fina\Sdk\Laravel\Reporting\Dto;

final class CycleReportResponseDto
{
    /**
     * @param  CycleReportRowDto[]  $reports
     */
    public function __construct(
        public readonly array $reports,
        public readonly ?string $ex,
    ) {}

    public static function fromArray(array $data): self
    {
        $rows = array_map(
            static fn ($r) => CycleReportRowDto::fromArray((array) $r),
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
            'reports' => array_map(static fn (CycleReportRowDto $r) => $r->toArray(), $this->reports),
            'ex' => $this->ex,
        ];
    }
}
