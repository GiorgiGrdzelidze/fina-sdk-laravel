<?php

declare(strict_types=1);

namespace Fina\Sdk\Laravel\Tests\Unit;

use Fina\Sdk\Laravel\Operation\Dto\AccountValueDetailDto;
use Fina\Sdk\Laravel\Operation\Dto\AutoServiceDocDto;
use Fina\Sdk\Laravel\Operation\Dto\ContragentSubAccountDto;
use Fina\Sdk\Laravel\Operation\Dto\ContragentSubAccountFieldDto;
use Fina\Sdk\Laravel\Operation\Dto\CustomerAgreementDto;
use Fina\Sdk\Laravel\Operation\Dto\GiftCardDto;
use Fina\Sdk\Laravel\Operation\Dto\GiftCardInfoDto;
use Fina\Sdk\Laravel\Operation\Dto\TransportationMeanDto;
use Fina\Sdk\Laravel\Reporting\Dto\AutoServicesOutJournalResponseDto;
use Fina\Sdk\Laravel\Reporting\Dto\AutoServicesOutJournalRowDto;
use Fina\Sdk\Laravel\Reporting\Dto\CafeOrderDetailedReportResponseDto;
use Fina\Sdk\Laravel\Reporting\Dto\CafeOrderDetailedReportRowDto;
use PHPUnit\Framework\TestCase;

final class NewDtoMappingTest extends TestCase
{
    public function test_gift_card_dto_includes_rest_amount(): void
    {
        $dto = GiftCardDto::fromArray([
            'id' => 1,
            'store' => 2,
            'code' => 'ABC',
            'acc' => '3121',
            'issuance_date' => '2024-01-01',
            'amount' => 100.0,
            'pay_amount' => 100.0,
            'rest_amount' => 85.5,
        ]);

        $this->assertSame(85.5, $dto->restAmount);
    }

    public function test_gift_card_dto_rest_amount_defaults_to_zero(): void
    {
        $dto = GiftCardDto::fromArray([
            'id' => 1,
            'store' => 2,
            'code' => 'ABC',
            'acc' => '3121',
            'issuance_date' => '2024-01-01',
            'amount' => 100.0,
            'pay_amount' => 100.0,
        ]);

        $this->assertSame(0.0, $dto->restAmount);
    }

    public function test_customer_agreement_dto_maps_fields(): void
    {
        $dto = CustomerAgreementDto::fromArray([
            'id' => 5,
            'contragent_id' => 2,
            'price_id' => 3,
            'name' => 'Agreement 1',
            'description' => 'Desc',
            'discount' => 10.5,
            'is_active' => true,
        ]);

        $this->assertSame(5, $dto->id);
        $this->assertSame(2, $dto->contragentId);
        $this->assertSame(3, $dto->priceId);
        $this->assertSame('Agreement 1', $dto->name);
        $this->assertSame(10.5, $dto->discount);
        $this->assertTrue($dto->isActive);
    }

    public function test_contragent_sub_account_field_dto_maps_fields(): void
    {
        $dto = ContragentSubAccountFieldDto::fromArray([
            'name' => 'usr_column_551',
            'header' => 'State Number',
        ]);

        $this->assertSame('usr_column_551', $dto->name);
        $this->assertSame('State Number', $dto->header);
    }

    public function test_contragent_sub_account_dto_maps_fields(): void
    {
        $dto = ContragentSubAccountDto::fromArray([
            'id' => 43,
            'contragent_id' => 837,
            'sub_accounts' => [
                ['field' => 'usr_column_551', 'value' => 'UBU115'],
                ['field' => 'usr_column_552', 'value' => 'WDB9540321K214212'],
            ],
        ]);

        $this->assertSame(43, $dto->id);
        $this->assertSame(837, $dto->contragentId);
        $this->assertCount(2, $dto->subAccounts);
        $this->assertSame('UBU115', $dto->subAccounts[0]['value']);
    }

    public function test_transportation_mean_dto_maps_fields(): void
    {
        $dto = TransportationMeanDto::fromArray([
            'id' => 1,
            'model' => 'alfa',
            'num' => 'xx123yy',
            'driver_name' => 'nika',
            'driver_num' => '0101020203',
            'fuel_consumption' => 12.0,
            'consumption_type' => 0,
            'staff_id' => 1,
            'trailer' => 'trailer1',
        ]);

        $this->assertSame(1, $dto->id);
        $this->assertSame('alfa', $dto->model);
        $this->assertSame('xx123yy', $dto->num);
        $this->assertSame('nika', $dto->driverName);
        $this->assertSame(12.0, $dto->fuelConsumption);
        $this->assertSame(0, $dto->consumptionType);
        $this->assertSame(1, $dto->staffId);
    }

    public function test_gift_card_info_dto_maps_fields(): void
    {
        $dto = GiftCardInfoDto::fromArray([
            'id' => 100009,
            'store' => 1,
            'code' => 'IQ41',
            'acc' => '3121',
            'issuance_date' => '2024-05-21 10:37:56',
            'amount' => 100.0,
            'pay_amount' => 100.0,
            'rest_amount' => 85.0,
        ]);

        $this->assertSame(100009, $dto->id);
        $this->assertSame('IQ41', $dto->code);
        $this->assertSame(85.0, $dto->restAmount);
    }

    public function test_account_value_detail_dto_maps_fields(): void
    {
        $dto = AccountValueDetailDto::fromArray([
            'id' => 2,
            'debit_val' => 1320.00,
            'credit_val' => 0.00,
        ]);

        $this->assertSame(2, $dto->id);
        $this->assertSame(1320.00, $dto->debitVal);
        $this->assertSame(0.00, $dto->creditVal);
    }

    public function test_auto_service_doc_dto_maps_fields(): void
    {
        $dto = AutoServiceDocDto::fromArray([
            'id' => 6563,
            'date' => '2019-05-16T15:28:19',
            'num_pfx' => '',
            'num' => 8624,
            'waybill_num' => '0418925004',
            'purpose' => 'Test',
            'amount' => 780.0,
            'currency' => 'GEL',
            'rate' => 1.0,
            'store' => 2,
            'customer' => 1,
            'user' => 1,
            'staff' => 1,
            'project' => 2,
            'is_vat' => true,
            'make_entry' => true,
            'pay_type' => 2,
            'w_type' => 2,
            't_type' => 1,
            't_payer' => 1,
            'w_cost' => 0.0,
            'foreign' => false,
            'drv_name' => 'driver',
            'tr_start' => 'start',
            'tr_end' => 'end',
            'driver_id' => '61006038420',
            'car_num' => 'iu100iu',
            'tr_text' => '',
            'sender' => '',
            'reciever' => '',
            'comment' => '',
            'mileage' => 946176,
            'in_date' => '2019-05-15T15:28:19',
            'box' => 0,
            'car' => 18023,
            'overlap_type' => 0,
            'overlap_amount' => 0.0,
            'add_fields' => [],
            'products' => [['id' => 29]],
            'services' => [],
            'sub_accounts' => [],
        ]);

        $this->assertSame(6563, $dto->id);
        $this->assertSame('0418925004', $dto->waybillNum);
        $this->assertSame(780.0, $dto->amount);
        $this->assertSame(946176, $dto->mileage);
        $this->assertSame(18023, $dto->car);
        $this->assertCount(1, $dto->products);
    }

    public function test_auto_services_out_journal_response_dto(): void
    {
        $dto = AutoServicesOutJournalResponseDto::fromArray([
            'journals' => [
                [
                    'id' => 5280,
                    'version' => 'AAAAAAAB3TM=',
                    'date' => '2022-12-27 18:10:48',
                    'in_date' => '2022-12-29 12:00:00',
                    'doc_num' => '36',
                    'waybill_num' => null,
                    'doc_type' => 107,
                    'purpose' => 'Test',
                    'amount' => 150.0,
                    'staff_id' => 3,
                    'customer_id' => 8,
                    'pay_type' => 0,
                ],
            ],
            'ex' => null,
        ]);

        $this->assertCount(1, $dto->journals);
        $this->assertNull($dto->ex);

        $row = $dto->journals[0];
        $this->assertInstanceOf(AutoServicesOutJournalRowDto::class, $row);
        $this->assertSame(5280, $row->id);
        $this->assertSame('2022-12-29 12:00:00', $row->inDate);
        $this->assertNull($row->waybillNum);
        $this->assertSame(107, $row->docType);
        $this->assertSame('idv:5280:AAAAAAAB3TM=', $row->dedupeKey());
    }

    public function test_cafe_order_detailed_report_response_dto(): void
    {
        $dto = CafeOrderDetailedReportResponseDto::fromArray([
            'reports' => [
                [
                    'date' => '2020-08-21T13:01:28.913',
                    'doc_num' => '20190228100000',
                    'store_id' => 1,
                    'status_id' => 1,
                    'product_id' => 1,
                    'group_id' => 1,
                    'unit_id' => 1,
                    'quantity' => 1.0,
                    'amount' => 2.5,
                ],
            ],
            'ex' => null,
        ]);

        $this->assertCount(1, $dto->reports);
        $this->assertNull($dto->ex);

        $row = $dto->reports[0];
        $this->assertInstanceOf(CafeOrderDetailedReportRowDto::class, $row);
        $this->assertSame(1, $row->statusId);
        $this->assertSame(1, $row->productId);
        $this->assertSame(2.5, $row->amount);
    }

    public function test_auto_services_journal_handles_empty(): void
    {
        $dto = AutoServicesOutJournalResponseDto::fromArray([
            'journals' => [],
            'ex' => null,
        ]);

        $this->assertCount(0, $dto->journals);
    }

    public function test_cafe_order_report_handles_empty(): void
    {
        $dto = CafeOrderDetailedReportResponseDto::fromArray([
            'reports' => [],
            'ex' => null,
        ]);

        $this->assertCount(0, $dto->reports);
    }
}
