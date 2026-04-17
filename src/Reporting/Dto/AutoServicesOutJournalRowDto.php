<?php

declare(strict_types=1);

/**
 * Reporting DTO: row from the Auto Services Out Journal response (v8.0).
 */

namespace Fina\Sdk\Laravel\Reporting\Dto;

use Fina\Sdk\Laravel\Reporting\ReportingApi;

/**
 * Represents a single row from getAutoServicesOutJournal.
 *
 * @see ReportingApi::autoServicesOutJournalTyped()
 */
final class AutoServicesOutJournalRowDto
{
    /**
     * @param  array<string, mixed>  $raw  Full raw API row for forward-compatibility.
     */
    public function __construct(
        public readonly int $id,
        public readonly string $version,
        public readonly string $date,
        public readonly string $inDate,
        public readonly string $docNum,
        public readonly ?string $waybillNum,
        public readonly int $docType,
        public readonly string $purpose,
        public readonly float $amount,
        public readonly int $staffId,
        public readonly int $customerId,
        public readonly int $payType,
        public readonly array $raw = [],
    ) {}

    /**
     * @param  array<string, mixed>  $row  Raw API row data.
     */
    public static function fromArray(array $row): self
    {
        return new self(
            id: (int) ($row['id'] ?? 0),
            version: (string) ($row['version'] ?? ''),
            date: (string) ($row['date'] ?? ''),
            inDate: (string) ($row['in_date'] ?? ''),
            docNum: (string) ($row['doc_num'] ?? ''),
            waybillNum: $row['waybill_num'] ?? null,
            docType: (int) ($row['doc_type'] ?? 0),
            purpose: (string) ($row['purpose'] ?? ''),
            amount: (float) ($row['amount'] ?? 0.0),
            staffId: (int) ($row['staff_id'] ?? 0),
            customerId: (int) ($row['customer_id'] ?? 0),
            payType: (int) ($row['pay_type'] ?? 0),
            raw: $row,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'version' => $this->version,
            'date' => $this->date,
            'in_date' => $this->inDate,
            'doc_num' => $this->docNum,
            'waybill_num' => $this->waybillNum,
            'doc_type' => $this->docType,
            'purpose' => $this->purpose,
            'amount' => $this->amount,
            'staff_id' => $this->staffId,
            'customer_id' => $this->customerId,
            'pay_type' => $this->payType,
            'raw' => $this->raw,
        ];
    }

    /**
     * Generate a stable deduplication key for chunk-merge operations.
     */
    public function dedupeKey(): string
    {
        return 'idv:'.$this->id.':'.$this->version;
    }
}
