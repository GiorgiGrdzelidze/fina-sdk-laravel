<?php

declare(strict_types=1);

namespace Fina\Sdk\Laravel\Tests\Unit;

use Carbon\CarbonImmutable;
use Fina\Sdk\Laravel\Client\FinaClient;
use Fina\Sdk\Laravel\Operation\Dto\AccountValueDetailDto;
use Fina\Sdk\Laravel\Operation\Dto\ContragentSubAccountDto;
use Fina\Sdk\Laravel\Operation\Dto\ContragentSubAccountFieldDto;
use Fina\Sdk\Laravel\Operation\Dto\CustomerAgreementDto;
use Fina\Sdk\Laravel\Operation\Dto\GiftCardInfoDto;
use Fina\Sdk\Laravel\Operation\Dto\TransportationMeanDto;
use Fina\Sdk\Laravel\Tests\TestCase;
use Illuminate\Support\Facades\Http;

final class NewEndpointsTest extends TestCase
{
    private function fakeAuth(): array
    {
        return ['token' => 'test-token', 'ex' => null];
    }

    private function makeClient(): FinaClient
    {
        return $this->app->make(FinaClient::class);
    }

    // -------------------------------------------------------
    // CustomersApi
    // -------------------------------------------------------

    public function test_customers_agreements(): void
    {
        Http::fake([
            '*/api/authentication/*' => Http::response($this->fakeAuth()),
            '*/api/operation/getCustomerAgreements' => Http::response([
                'agreements' => [
                    ['id' => 5, 'contragent_id' => 2, 'price_id' => 3, 'name' => 'A1', 'description' => '', 'discount' => 10.0, 'is_active' => true],
                ],
                'ex' => null,
            ]),
        ]);

        $result = $this->makeClient()->customers()->agreements();

        $this->assertCount(1, $result);
        $this->assertInstanceOf(CustomerAgreementDto::class, $result[0]);
        $this->assertSame(5, $result[0]->id);
    }

    public function test_customers_sub_account_fields(): void
    {
        Http::fake([
            '*/api/authentication/*' => Http::response($this->fakeAuth()),
            '*/api/operation/getContragentSubAccountFields' => Http::response([
                'fields' => [
                    ['name' => 'usr_column_551', 'header' => 'State Number'],
                ],
                'ex' => null,
            ]),
        ]);

        $result = $this->makeClient()->customers()->subAccountFields();

        $this->assertCount(1, $result);
        $this->assertInstanceOf(ContragentSubAccountFieldDto::class, $result[0]);
        $this->assertSame('usr_column_551', $result[0]->name);
    }

    public function test_customers_sub_accounts(): void
    {
        Http::fake([
            '*/api/authentication/*' => Http::response($this->fakeAuth()),
            '*/api/operation/getCustomerSubAccounts' => Http::response([
                'contragent_sub_accounts' => [
                    ['id' => 43, 'contragent_id' => 837, 'sub_accounts' => [['field' => 'f1', 'value' => 'v1']]],
                ],
                'ex' => null,
            ]),
        ]);

        $result = $this->makeClient()->customers()->subAccounts();

        $this->assertCount(1, $result);
        $this->assertInstanceOf(ContragentSubAccountDto::class, $result[0]);
        $this->assertSame(43, $result[0]->id);
    }

    // -------------------------------------------------------
    // VendorsApi
    // -------------------------------------------------------

    public function test_vendors_sub_accounts(): void
    {
        Http::fake([
            '*/api/authentication/*' => Http::response($this->fakeAuth()),
            '*/api/operation/getVendorSubAccounts' => Http::response([
                'contragent_sub_accounts' => [
                    ['id' => 143, 'contragent_id' => 1837, 'sub_accounts' => []],
                ],
                'ex' => null,
            ]),
        ]);

        $result = $this->makeClient()->vendors()->subAccounts();

        $this->assertCount(1, $result);
        $this->assertInstanceOf(ContragentSubAccountDto::class, $result[0]);
    }

    // -------------------------------------------------------
    // ProductsApi
    // -------------------------------------------------------

    public function test_products_rest_after(): void
    {
        Http::fake([
            '*/api/authentication/*' => Http::response($this->fakeAuth()),
            '*/api/operation/getProductsRestAfter/*' => Http::response([
                'rests' => [['id' => 1, 'rest' => 5.0]],
                'ex' => null,
            ]),
        ]);

        $result = $this->makeClient()->products()->restAfter(CarbonImmutable::parse('2025-01-01'));

        $this->assertArrayHasKey('rests', $result);
    }

    public function test_products_rest_summary(): void
    {
        Http::fake([
            '*/api/authentication/*' => Http::response($this->fakeAuth()),
            '*/api/operation/getProductsRestSummary' => Http::response([
                'store_rest' => [['id' => 1, 'rest' => 10.0]],
                'ex' => null,
            ]),
        ]);

        $result = $this->makeClient()->products()->restSummary([1, 2], [1], CarbonImmutable::parse('2025-01-01'));

        $this->assertArrayHasKey('store_rest', $result);
    }

    public function test_products_rest_by_store_after(): void
    {
        Http::fake([
            '*/api/authentication/*' => Http::response($this->fakeAuth()),
            '*/api/operation/getProductsRestByStoreAfter/*' => Http::response([
                'store_rest' => [['id' => 1, 'rest' => 3.0]],
                'ex' => null,
            ]),
        ]);

        $result = $this->makeClient()->products()->restByStoreAfter(1, CarbonImmutable::parse('2025-01-01'));

        $this->assertArrayHasKey('store_rest', $result);
    }

    public function test_products_provided_service_prices(): void
    {
        Http::fake([
            '*/api/authentication/*' => Http::response($this->fakeAuth()),
            '*/api/operation/getProvidedServicePrices' => Http::response([
                'prices' => [['id' => 1, 'price' => 50.0]],
                'ex' => null,
            ]),
        ]);

        $result = $this->makeClient()->products()->providedServicePrices();

        $this->assertArrayHasKey('prices', $result);
    }

    // -------------------------------------------------------
    // LoyaltyApi
    // -------------------------------------------------------

    public function test_loyalty_gift_card_info_by_code(): void
    {
        Http::fake([
            '*/api/authentication/*' => Http::response($this->fakeAuth()),
            '*/api/operation/getGiftCardInfoByCode/*' => Http::response([
                'gift' => [
                    'id' => 100009, 'store' => 1, 'code' => 'IQ41', 'acc' => '3121',
                    'issuance_date' => '2024-05-21', 'amount' => 100.0, 'pay_amount' => 100.0, 'rest_amount' => 85.0,
                ],
                'ex' => null,
            ]),
        ]);

        $result = $this->makeClient()->loyalty()->giftCardInfoByCode('IQ41');

        $this->assertInstanceOf(GiftCardInfoDto::class, $result);
        $this->assertSame(100009, $result->id);
        $this->assertSame(85.0, $result->restAmount);
    }

    public function test_loyalty_bonus_card_rest_by_code(): void
    {
        Http::fake([
            '*/api/authentication/*' => Http::response($this->fakeAuth()),
            '*/api/operation/getBonusCardRestByCode/*' => Http::response([
                'rest' => 150.5,
                'ex' => null,
            ]),
        ]);

        $result = $this->makeClient()->loyalty()->bonusCardRestByCode('CARD123');

        $this->assertArrayHasKey('rest', $result);
    }

    // -------------------------------------------------------
    // ReferenceApi
    // -------------------------------------------------------

    public function test_reference_transportation_means(): void
    {
        Http::fake([
            '*/api/authentication/*' => Http::response($this->fakeAuth()),
            '*/api/operation/getTransportationMeans' => Http::response([
                'transportation_means' => [
                    ['id' => 1, 'model' => 'alfa', 'num' => 'xx123', 'driver_name' => 'nika',
                        'driver_num' => '010', 'fuel_consumption' => 12.0, 'consumption_type' => 0,
                        'staff_id' => 1, 'trailer' => ''],
                ],
                'ex' => null,
            ]),
        ]);

        $result = $this->makeClient()->reference()->transportationMeans();

        $this->assertCount(1, $result);
        $this->assertInstanceOf(TransportationMeanDto::class, $result[0]);
        $this->assertSame('alfa', $result[0]->model);
    }

    public function test_reference_account_value_details(): void
    {
        Http::fake([
            '*/api/authentication/*' => Http::response($this->fakeAuth()),
            '*/api/operation/getAccountValueDetails' => Http::response([
                'values' => [
                    ['id' => 2, 'debit_val' => 1320.00, 'credit_val' => 0.00],
                    ['id' => 9, 'debit_val' => 0.00, 'credit_val' => 150.00],
                ],
                'ex' => null,
            ]),
        ]);

        $result = $this->makeClient()->reference()->accountValueDetails(
            '1410',
            CarbonImmutable::parse('2023-11-28'),
            'USD'
        );

        $this->assertCount(2, $result);
        $this->assertInstanceOf(AccountValueDetailDto::class, $result[0]);
        $this->assertSame(1320.00, $result[0]->debitVal);
    }

    // -------------------------------------------------------
    // DocumentsApi
    // -------------------------------------------------------

    public function test_documents_get_auto_service(): void
    {
        Http::fake([
            '*/api/authentication/*' => Http::response($this->fakeAuth()),
            '*/api/operation/getDocAutoService/*' => Http::response([
                'autoservice' => [
                    'id' => 6563, 'date' => '2019-05-16', 'num_pfx' => '', 'num' => 8624,
                    'waybill_num' => '04189', 'purpose' => 'Test', 'amount' => 780.0,
                    'currency' => 'GEL', 'rate' => 1.0, 'store' => 2, 'customer' => 1,
                    'user' => 1, 'staff' => 1, 'project' => 2, 'is_vat' => true,
                    'make_entry' => true, 'pay_type' => 2, 'w_type' => 2, 't_type' => 1,
                    't_payer' => 1, 'w_cost' => 0.0, 'foreign' => false,
                    'drv_name' => '', 'tr_start' => '', 'tr_end' => '',
                    'driver_id' => '', 'car_num' => '', 'tr_text' => '',
                    'sender' => '', 'reciever' => '', 'comment' => '',
                    'mileage' => 946176, 'in_date' => '2019-05-15', 'box' => 0, 'car' => 18023,
                    'overlap_type' => 0, 'overlap_amount' => 0.0,
                    'add_fields' => [], 'products' => [], 'services' => [], 'sub_accounts' => [],
                ],
                'ex' => null,
            ]),
        ]);

        $result = $this->makeClient()->documents()->getAutoServiceTyped(6563);

        $this->assertSame(6563, $result->id);
        $this->assertSame(946176, $result->mileage);
    }

    public function test_documents_save_bonus_card(): void
    {
        Http::fake([
            '*/api/authentication/*' => Http::response($this->fakeAuth()),
            '*/api/operation/saveDocBonusCard' => Http::response(['id' => 3, 'ex' => null]),
        ]);

        $result = $this->makeClient()->documents()->saveBonusCard([
            'id' => 0, 'date' => '2024-01-01T00:00:00', 'num_pfx' => '', 'num' => 0,
            'purpose' => 'Test', 'customer' => 1, 'store' => 1, 'user' => 1,
            'card_code' => '123', 'person_code' => '010', 'person_name' => 'Test',
            'person_address' => '', 'person_tel' => '', 'status' => true,
        ]);

        $this->assertSame(3, $result->id);
    }

    public function test_documents_save_gift_payment(): void
    {
        Http::fake([
            '*/api/authentication/*' => Http::response($this->fakeAuth()),
            '*/api/operation/saveDocGiftPayment' => Http::response(['id' => 5, 'ex' => null]),
        ]);

        $result = $this->makeClient()->documents()->saveGiftPayment([
            'date' => '2024-05-22T13:00:00', 'card_id' => 100009, 'num_pfx' => '',
            'num' => 0, 'purpose' => 'Test', 'amount' => 20.4, 'store' => 1,
            'user' => 1, 'staff' => 3, 'project' => 2, 'customer' => 8,
            'ref_id' => 133, 'make_entry' => true,
        ]);

        $this->assertSame(5, $result->id);
    }

    public function test_documents_save_received_service(): void
    {
        Http::fake([
            '*/api/authentication/*' => Http::response($this->fakeAuth()),
            '*/api/operation/saveDocReceivedService' => Http::response(['id' => 7, 'ex' => null]),
        ]);

        $result = $this->makeClient()->documents()->saveReceivedService([
            'id' => 0, 'date' => '2024-01-01T00:00:00', 'num_pfx' => '', 'num' => 0,
            'purpose' => 'Test', 'amount' => 2.0, 'currency' => 'GEL', 'rate' => 1.0,
            'user' => 2, 'project' => 2, 'vendor' => 3, 'is_vat' => true,
            'make_entry' => true, 'pay_type' => 1, 'services' => [['id' => 2, 'quantity' => 1.0, 'price' => 2.0]],
        ]);

        $this->assertSame(7, $result->id);
    }

    // -------------------------------------------------------
    // JournalsApi
    // -------------------------------------------------------

    public function test_journals_auto_services_out(): void
    {
        Http::fake([
            '*/api/authentication/*' => Http::response($this->fakeAuth()),
            '*/api/reporting/getAutoServicesOutJournal/*' => Http::response([
                'journals' => [['id' => 1, 'version' => 'v1', 'date' => '2024-01-01', 'doc_num' => '1', 'doc_type' => 107, 'purpose' => 'Test', 'amount' => 100.0, 'ex' => null]],
                'ex' => null,
            ]),
        ]);

        $from = CarbonImmutable::parse('2024-01-01');
        $to = CarbonImmutable::parse('2024-01-31');

        $result = $this->makeClient()->journals()->autoServicesOut($from, $to);

        $this->assertArrayHasKey('journals', $result);
    }

    // -------------------------------------------------------
    // ReportingApi (typed)
    // -------------------------------------------------------

    public function test_reporting_auto_services_out_journal_typed(): void
    {
        Http::fake([
            '*/api/authentication/*' => Http::response($this->fakeAuth()),
            '*/api/reporting/getAutoServicesOutJournal/*' => Http::response([
                'journals' => [
                    ['id' => 5280, 'version' => 'v1', 'date' => '2024-01-01', 'in_date' => '2024-01-02',
                        'doc_num' => '36', 'waybill_num' => null, 'doc_type' => 107,
                        'purpose' => 'Test', 'amount' => 150.0, 'staff_id' => 3,
                        'customer_id' => 8, 'pay_type' => 0],
                ],
                'ex' => null,
            ]),
        ]);

        $from = CarbonImmutable::parse('2024-01-01');
        $to = CarbonImmutable::parse('2024-01-31');

        $result = $this->makeClient()->reporting()->autoServicesOutJournalTyped($from, $to);

        $this->assertCount(1, $result->journals);
        $this->assertSame(5280, $result->journals[0]->id);
    }

    public function test_reporting_cafe_order_detailed_report_typed(): void
    {
        Http::fake([
            '*/api/authentication/*' => Http::response($this->fakeAuth()),
            '*/api/reporting/getCafeOrderDetailedReport/*' => Http::response([
                'reports' => [
                    ['date' => '2020-08-21', 'doc_num' => '123', 'store_id' => 1,
                        'status_id' => 2, 'product_id' => 1, 'group_id' => 1,
                        'unit_id' => 1, 'quantity' => 1.0, 'amount' => 2.5],
                ],
                'ex' => null,
            ]),
        ]);

        $from = CarbonImmutable::parse('2024-01-01');
        $to = CarbonImmutable::parse('2024-01-31');

        $result = $this->makeClient()->reporting()->cafeOrderDetailedReportTyped($from, $to);

        $this->assertCount(1, $result->reports);
        $this->assertSame(2, $result->reports[0]->statusId);
    }
}
