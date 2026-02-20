<?php

declare(strict_types=1);

namespace Fina\Sdk\Laravel\Tests\Unit;

use Fina\Sdk\Laravel\Auth\AuthTokenResponse;
use Fina\Sdk\Laravel\Operation\Dto\SaveDocResponse;
use Fina\Sdk\Laravel\Reporting\Dto\CycleReportResponseDto;
use Fina\Sdk\Laravel\Reporting\Dto\CycleReportRowDto;
use Fina\Sdk\Laravel\Reporting\Dto\EntriesJournalResponseDto;
use Fina\Sdk\Laravel\Reporting\Dto\EntriesJournalRowDto;
use Fina\Sdk\Laravel\Reporting\Dto\MoneyJournalResponseDto;
use Fina\Sdk\Laravel\Reporting\Dto\MoneyJournalRowDto;
use Fina\Sdk\Laravel\Reporting\Dto\ProductsInReturnReportResponseDto;
use Fina\Sdk\Laravel\Reporting\Dto\ProductsInReturnReportRowDto;
use PHPUnit\Framework\TestCase;

final class DtoMappingTest extends TestCase
{
    // -----------------------------------------------------------------
    // CycleReportResponseDto
    // -----------------------------------------------------------------

    public function test_cycle_report_maps_fields(): void
    {
        $raw = [
            'reports' => [
                ['id' => 1, 'start_val' => 100.0, 'in_val' => 50.0, 'out_val' => 30.0, 'end_val' => 120.0],
                ['id' => 2, 'start_val' => 200.0, 'in_val' => 0.0, 'out_val' => 10.0, 'end_val' => 190.0],
            ],
            'ex' => null,
        ];

        $dto = CycleReportResponseDto::fromArray($raw);

        $this->assertCount(2, $dto->reports);
        $this->assertNull($dto->ex);

        $this->assertInstanceOf(CycleReportRowDto::class, $dto->reports[0]);
        $this->assertSame(1, $dto->reports[0]->id);
        $this->assertSame(100.0, $dto->reports[0]->startValue);
        $this->assertSame(50.0, $dto->reports[0]->inValue);
        $this->assertSame(30.0, $dto->reports[0]->outValue);
        $this->assertSame(120.0, $dto->reports[0]->endValue);

        $this->assertSame(2, $dto->reports[1]->id);
        $this->assertSame(190.0, $dto->reports[1]->endValue);
    }

    public function test_cycle_report_preserves_ex(): void
    {
        $dto = CycleReportResponseDto::fromArray([
            'reports' => [],
            'ex' => 'some error',
        ]);

        $this->assertSame('some error', $dto->ex);
        $this->assertCount(0, $dto->reports);
    }

    // -----------------------------------------------------------------
    // MoneyJournalResponseDto
    // -----------------------------------------------------------------

    public function test_money_journal_maps_fields(): void
    {
        $raw = [
            'journals' => [
                [
                    'id' => 10,
                    'version' => 'v1',
                    'date' => '2025-01-15T10:00:00',
                    'doc_num' => 'MJ-001',
                    'doc_type' => 3,
                    'purpose' => 'Payment',
                    'amount' => 500.0,
                    'staff_id' => 5,
                    'currency' => 'GEL',
                    'customer_id' => 42,
                    'vendor_id' => null,
                    'pay_type' => 1,
                    'pay_type_id' => 2,
                ],
            ],
            'ex' => null,
        ];

        $dto = MoneyJournalResponseDto::fromArray($raw);

        $this->assertCount(1, $dto->journals);
        $this->assertNull($dto->ex);

        $row = $dto->journals[0];
        $this->assertInstanceOf(MoneyJournalRowDto::class, $row);
        $this->assertSame(10, $row->id);
        $this->assertSame('v1', $row->version);
        $this->assertSame('MJ-001', $row->docNum);
        $this->assertSame(3, $row->docType);
        $this->assertSame(500.0, $row->amount);
        $this->assertSame(42, $row->customerId);
        $this->assertNull($row->vendorId);
        $this->assertSame('GEL', $row->currency);
    }

    // -----------------------------------------------------------------
    // EntriesJournalResponseDto
    // -----------------------------------------------------------------

    public function test_entries_journal_maps_fields(): void
    {
        $raw = [
            'journals' => [
                [
                    'id' => 99,
                    'version' => 'abc',
                    'date' => '2025-03-01T00:00:00',
                    'doc_num' => 'EJ-100',
                    'doc_type' => 7,
                    'purpose' => 'Accounting entry',
                    'amount' => 1234.56,
                ],
            ],
            'ex' => null,
        ];

        $dto = EntriesJournalResponseDto::fromArray($raw);

        $this->assertCount(1, $dto->journals);
        $row = $dto->journals[0];
        $this->assertInstanceOf(EntriesJournalRowDto::class, $row);
        $this->assertSame(99, $row->id);
        $this->assertSame('abc', $row->version);
        $this->assertSame('EJ-100', $row->docNum);
        $this->assertSame(7, $row->docType);
        $this->assertSame(1234.56, $row->amount);
    }

    public function test_entries_journal_row_dedupe_key(): void
    {
        $row = EntriesJournalRowDto::fromArray([
            'id' => 5,
            'version' => 'xyz',
        ]);

        $this->assertSame('idv:5:xyz', $row->dedupeKey());
    }

    public function test_entries_journal_to_array_roundtrip(): void
    {
        $raw = [
            'id' => 1,
            'version' => 'v',
            'date' => '2025-01-01',
            'doc_num' => 'D1',
            'doc_type' => 2,
            'purpose' => 'test',
            'amount' => 10.0,
        ];

        $row = EntriesJournalRowDto::fromArray($raw);
        $arr = $row->toArray();

        $this->assertSame(1, $arr['id']);
        $this->assertSame('v', $arr['version']);
        $this->assertSame('D1', $arr['doc_num']);
        $this->assertSame(2, $arr['doc_type']);
        $this->assertSame(10.0, $arr['amount']);
        $this->assertArrayHasKey('raw', $arr);
    }

    // -----------------------------------------------------------------
    // ProductsInReturnReportResponseDto (was empty file — regression test)
    // -----------------------------------------------------------------

    public function test_products_in_return_report_maps_fields(): void
    {
        $raw = [
            'reports' => [
                [
                    'id' => 1,
                    'name' => 'Widget',
                    'barcode' => '123456',
                    'in_qty' => 100.0,
                    'ret_qty' => 5.0,
                    'diff_qty' => 95.0,
                ],
            ],
            'ex' => null,
        ];

        $dto = ProductsInReturnReportResponseDto::fromArray($raw);

        $this->assertCount(1, $dto->reports);
        $this->assertNull($dto->ex);

        $row = $dto->reports[0];
        $this->assertInstanceOf(ProductsInReturnReportRowDto::class, $row);
        $this->assertSame(1, $row->id);
        $this->assertSame('Widget', $row->name);
        $this->assertSame(100.0, $row->inQuantity);
        $this->assertSame(5.0, $row->returnQuantity);
        $this->assertSame(95.0, $row->diffQuantity);
    }

    // -----------------------------------------------------------------
    // SaveDocResponse
    // -----------------------------------------------------------------

    public function test_save_doc_response_maps_fields(): void
    {
        $dto = SaveDocResponse::fromArray(['id' => 42, 'ex' => null]);

        $this->assertSame(42, $dto->id);
        $this->assertNull($dto->ex);
    }

    public function test_save_doc_response_with_error(): void
    {
        $dto = SaveDocResponse::fromArray(['id' => 0, 'ex' => 'Something went wrong']);

        $this->assertSame(0, $dto->id);
        $this->assertSame('Something went wrong', $dto->ex);
    }

    // -----------------------------------------------------------------
    // AuthTokenResponse
    // -----------------------------------------------------------------

    public function test_auth_token_response_maps_token(): void
    {
        $dto = AuthTokenResponse::fromArray(['token' => 'abc123', 'ex' => null]);

        $this->assertSame('abc123', $dto->token);
        $this->assertNull($dto->ex);
    }

    public function test_auth_token_response_empty_token(): void
    {
        $dto = AuthTokenResponse::fromArray(['token' => '', 'ex' => 'Invalid credentials']);

        $this->assertNull($dto->token);
        $this->assertSame('Invalid credentials', $dto->ex);
    }

    // -----------------------------------------------------------------
    // Empty / missing data graceful handling
    // -----------------------------------------------------------------

    public function test_entries_journal_handles_empty_array(): void
    {
        $dto = EntriesJournalResponseDto::fromArray([]);

        $this->assertCount(0, $dto->journals);
        $this->assertNull($dto->ex);
    }

    public function test_cycle_report_handles_empty_array(): void
    {
        $dto = CycleReportResponseDto::fromArray([]);

        $this->assertCount(0, $dto->reports);
        $this->assertNull($dto->ex);
    }
}
