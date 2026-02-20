<?php

declare(strict_types=1);

/**
 * Reporting DTO: row from the Entries Journal response.
 */

namespace Fina\Sdk\Laravel\Reporting\Dto;

/**
 * Accounting entries journal row (getEntriesJournal -> journals[]).
 *
 * Fields may vary slightly across installs; we type the common core and keep a fallback payload.
 */
final class EntriesJournalRowDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $version,
        public readonly string $date,
        public readonly string $docNum,
        public readonly int $docType,
        public readonly string $purpose,
        public readonly float $amount,
        public readonly array $raw = [],
    ) {}

    public static function fromArray(array $row): self
    {
        return new self(
            id: (int) ($row['id'] ?? 0),
            version: (string) ($row['version'] ?? ''),
            date: (string) ($row['date'] ?? ''),
            docNum: (string) ($row['doc_num'] ?? ($row['docNum'] ?? '')),
            docType: (int) ($row['doc_type'] ?? ($row['docType'] ?? 0)),
            purpose: (string) ($row['purpose'] ?? ''),
            amount: (float) ($row['amount'] ?? 0.0),
            raw: $row,
        );
    }

    public function toArray(): array
    {
        // Keep raw for forward compatibility, but also expose normalized keys.
        return [
            'id' => $this->id,
            'version' => $this->version,
            'date' => $this->date,
            'doc_num' => $this->docNum,
            'doc_type' => $this->docType,
            'purpose' => $this->purpose,
            'amount' => $this->amount,
            'raw' => $this->raw,
        ];
    }

    public function dedupeKey(): string
    {
        // Most stable for chunk merge
        return 'idv:'.$this->id.':'.$this->version;
    }
}
