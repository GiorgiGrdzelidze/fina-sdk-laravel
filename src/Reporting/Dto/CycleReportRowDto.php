<?php

declare(strict_types=1);

/**
 * Reporting DTO: row from the Cycle Report response.
 */

namespace Fina\Sdk\Laravel\Reporting\Dto;

final class CycleReportRowDto
{
    public function __construct(
        public readonly int $id,
        public readonly float $startValue,
        public readonly float $inValue,
        public readonly float $outValue,
        public readonly float $endValue,
    ) {}

    public static function fromArray(array $row): self
    {
        return new self(
            id: (int) ($row['id'] ?? 0),
            startValue: (float) ($row['start_val'] ?? 0.0),
            inValue: (float) ($row['in_val'] ?? 0.0),
            outValue: (float) ($row['out_val'] ?? 0.0),
            endValue: (float) ($row['end_val'] ?? 0.0),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'start_val' => $this->startValue,
            'in_val' => $this->inValue,
            'out_val' => $this->outValue,
            'end_val' => $this->endValue,
        ];
    }
}
