<?php

declare(strict_types=1);

/**
 * Reporting DTO: row from the Customers Order Journal response.
 */

namespace Fina\Sdk\Laravel\Reporting\Dto;

final class CustomersOrderJournalRowDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $version,
        public readonly string $date,
        public readonly string $docNum,
        public readonly int $docType,
        public readonly int $customerId,
        public readonly string $customerName,
        public readonly float $amount,
        public readonly string $currency,
        public readonly string $purpose,
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
            customerId: (int) ($row['customer_id'] ?? 0),
            customerName: (string) ($row['customer_name'] ?? ($row['customer'] ?? '')),
            amount: (float) ($row['amount'] ?? 0.0),
            currency: (string) ($row['currency'] ?? ''),
            purpose: (string) ($row['purpose'] ?? ''),
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
            'customer_id' => $this->customerId,
            'customer_name' => $this->customerName,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'purpose' => $this->purpose,
            'raw' => $this->raw,
        ];
    }

    public function dedupeKey(): string
    {
        return 'idv:'.$this->id.':'.$this->version;
    }
}
