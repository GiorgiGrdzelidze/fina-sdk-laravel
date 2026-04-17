<?php

declare(strict_types=1);

/**
 * Reporting DTO: typed response wrapper for the Auto Services Out Journal (v8.0).
 */

namespace Fina\Sdk\Laravel\Reporting\Dto;

use Fina\Sdk\Laravel\Reporting\ReportingApi;

/**
 * Wraps the getAutoServicesOutJournal response into typed row DTOs.
 *
 * @see ReportingApi::autoServicesOutJournalTyped()
 */
final class AutoServicesOutJournalResponseDto
{
    /**
     * @param  AutoServicesOutJournalRowDto[]  $journals
     */
    public function __construct(
        public readonly array $journals,
        public readonly ?string $ex,
    ) {}

    /**
     * @param  array<string, mixed>  $data  Raw API response array.
     */
    public static function fromArray(array $data): self
    {
        $rows = array_map(
            static fn ($r) => AutoServicesOutJournalRowDto::fromArray((array) $r),
            (array) ($data['journals'] ?? [])
        );

        return new self(
            journals: $rows,
            ex: isset($data['ex']) ? (is_null($data['ex']) ? null : (string) $data['ex']) : null,
        );
    }

    public function toArray(): array
    {
        return [
            'journals' => array_map(static fn (AutoServicesOutJournalRowDto $r) => $r->toArray(), $this->journals),
            'ex' => $this->ex,
        ];
    }
}
