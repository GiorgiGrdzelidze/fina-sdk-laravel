<?php

declare(strict_types=1);

/**
 * Reporting DTO: row from the Discount Cards Journal response.
 */

namespace Fina\Sdk\Laravel\Reporting\Dto;

final class DiscountCardsJournalRowDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $version,
        public readonly string $date,
        public readonly string $docNum,
        public readonly int $docType,
        public readonly string $purpose,
        public readonly float $amount,
        public readonly string $currency,
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
            currency: (string) ($row['currency'] ?? ''),
            raw: $row,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'version' => $this->version,
            'date' => $this->date,
            'doc_num' => $this->docNum,
            'doc_type' => $this->docType,
            'purpose' => $this->purpose,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'raw' => $this->raw,
        ];
    }

    public function dedupeKey(): string
    {
        return 'idv:'.$this->id.':'.$this->version;
    }
}
