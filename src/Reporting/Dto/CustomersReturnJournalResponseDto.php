<?php

declare(strict_types=1);

/**
 * Reporting DTO: typed response wrapper for the Customers Return Journal.
 */

namespace Fina\Sdk\Laravel\Reporting\Dto;

final class CustomersReturnJournalResponseDto
{
    /**
     * @param  CustomersReturnJournalRowDto[]  $journals
     */
    public function __construct(
        public readonly array $journals,
        public readonly ?string $ex,
    ) {}

    public static function fromArray(array $data): self
    {
        $rows = array_map(
            static fn ($r) => CustomersReturnJournalRowDto::fromArray((array) $r),
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
            'journals' => array_map(static fn (CustomersReturnJournalRowDto $r) => $r->toArray(), $this->journals),
            'ex' => $this->ex,
        ];
    }
}
