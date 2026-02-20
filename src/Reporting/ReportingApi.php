<?php

declare(strict_types=1);

/**
 * Full-featured Reporting API client with raw, typed, and chunked methods.
 */

namespace Fina\Sdk\Laravel\Reporting;

use Carbon\CarbonImmutable;
use DateTimeInterface;
use Fina\Sdk\Laravel\Endpoints\BaseApi;
use Fina\Sdk\Laravel\Reporting\Dto\CustomersOrderJournalResponseDto;
use Fina\Sdk\Laravel\Reporting\Dto\CustomersReturnJournalResponseDto;
use Fina\Sdk\Laravel\Reporting\Dto\CycleReportResponseDto;
use Fina\Sdk\Laravel\Reporting\Dto\DiscountCardsJournalResponseDto;
use Fina\Sdk\Laravel\Reporting\Dto\EntriesJournalResponseDto;
use Fina\Sdk\Laravel\Reporting\Dto\MoneyJournalResponseDto;
use Fina\Sdk\Laravel\Reporting\Dto\ProductionsJournalResponseDto;
use Fina\Sdk\Laravel\Reporting\Dto\ProductsInReturnReportResponseDto;
use Fina\Sdk\Laravel\Reporting\Dto\ProductsLastInReportResponseDto;
use Fina\Sdk\Laravel\Reporting\Dto\ProvidedServicesJournalResponseDto;
use Fina\Sdk\Laravel\Reporting\Dto\ReceivedServicesJournalResponseDto;
use Fina\Sdk\Laravel\Support\FinaDate;

/**
 * Provides raw, typed, and chunked access to all FINA Reporting API endpoints.
 *
 * Chunked methods split large date ranges into smaller windows to avoid
 * API timeouts, then merge and deduplicate the results.
 */
final class ReportingApi extends BaseApi
{
    public function __construct(\Fina\Sdk\Laravel\Client\FinaClient $client)
    {
        parent::__construct($client, 'reporting');
    }

    /**
     * Low-level helper:
     * GET api/reporting/{method}/{date_from}/{date_to}
     * date format required: yyyy-MM-ddTHH:mm:ss
     */
    public function getRange(string $method, DateTimeInterface $from, DateTimeInterface $to): array
    {
        $fromStr = FinaDate::toFinaDateTime($from);
        $toStr = FinaDate::toFinaDateTime($to);

        return $this->get(
            $method.'/'.rawurlencode($fromStr).'/'.rawurlencode($toStr),
            [],
            "reporting.{$method} returned ex"
        );
    }

    /**
     * Chunk + merge + dedupe by stable key.
     *
     * @param  string  $method  reporting method name (e.g. getEntriesJournal)
     * @param  string  $collectionKey  response key to merge (e.g. 'journals' or 'reports')
     * @param  callable|null  $dedupeKeyFn  fn(array $item): string
     */
    public function getRangeChunked(
        string $method,
        string $collectionKey,
        DateTimeInterface $from,
        DateTimeInterface $to,
        int $chunkDays = 7,
        ?callable $dedupeKeyFn = null
    ): array {
        $start = CarbonImmutable::instance($from)->startOfDay();
        $end = CarbonImmutable::instance($to);

        $seen = [];
        $all = [];
        $last = null;

        for ($cursor = $start; $cursor->lt($end); $cursor = $cursor->addDays($chunkDays)) {
            $chunkFrom = $cursor;
            $chunkTo = $cursor->addDays($chunkDays)->min($end);

            $resp = $this->getRange($method, $chunkFrom, $chunkTo);
            $last = $resp;

            $items = (array) ($resp[$collectionKey] ?? []);
            foreach ($items as $item) {
                $itemArr = (array) $item;

                $key = $dedupeKeyFn
                    ? (string) $dedupeKeyFn($itemArr)
                    : $this->defaultDedupeKey($itemArr);

                if (! isset($seen[$key])) {
                    $seen[$key] = true;
                    $all[] = $itemArr;
                }
            }
        }

        return [
            $collectionKey => $all,
            'ex' => $last['ex'] ?? null,
        ];
    }

    private function defaultDedupeKey(array $item): string
    {
        // Journals often return id+version — most stable dedupe key
        if (isset($item['id']) && isset($item['version'])) {
            return 'idv:'.(string) $item['id'].':'.(string) $item['version'];
        }

        if (isset($item['id'])) {
            return 'id:'.(string) $item['id'];
        }

        return 'h:'.sha1(json_encode($item, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '');
    }

    // ---------------------------------------------------------------------
    // Journals (raw)
    // ---------------------------------------------------------------------

    /** getCustomersOrderJournal (raw) */
    public function customersOrderJournal(DateTimeInterface $from, DateTimeInterface $to): array
    {
        return $this->getRange('getCustomersOrderJournal', $from, $to);
    }

    /** getCustomersReturnJournal (raw) */
    public function customersReturnJournal(DateTimeInterface $from, DateTimeInterface $to): array
    {
        return $this->getRange('getCustomersReturnJournal', $from, $to);
    }

    /** getCustomersMoneyJournal (raw) */
    public function customersMoneyJournal(DateTimeInterface $from, DateTimeInterface $to): array
    {
        return $this->getRange('getCustomersMoneyJournal', $from, $to);
    }

    /** getVendorsMoneyJournal (raw) */
    public function vendorsMoneyJournal(DateTimeInterface $from, DateTimeInterface $to): array
    {
        return $this->getRange('getVendorsMoneyJournal', $from, $to);
    }

    /** getProductionsJournal (raw) */
    public function productionsJournal(DateTimeInterface $from, DateTimeInterface $to): array
    {
        return $this->getRange('getProductionsJournal', $from, $to);
    }

    /** getEntriesJournal (raw) */
    public function entriesJournal(DateTimeInterface $from, DateTimeInterface $to): array
    {
        return $this->getRange('getEntriesJournal', $from, $to);
    }

    /** getDiscountCardsJournal (raw) */
    public function discountCardsJournal(DateTimeInterface $from, DateTimeInterface $to): array
    {
        return $this->getRange('getDiscountCardsJournal', $from, $to);
    }

    /** getDocProvidedServicesJournal (raw) */
    public function providedServicesJournal(DateTimeInterface $from, DateTimeInterface $to): array
    {
        return $this->getRange('getDocProvidedServicesJournal', $from, $to);
    }

    /** getDocReceivedServicesJournal (raw) */
    public function receivedServicesJournal(DateTimeInterface $from, DateTimeInterface $to): array
    {
        return $this->getRange('getDocReceivedServicesJournal', $from, $to);
    }

    /** getRealizesJournal (raw) */
    public function realizesJournal(DateTimeInterface $from, DateTimeInterface $to): array
    {
        return $this->getRange('getRealizesJournal', $from, $to);
    }

    // ---------------------------------------------------------------------
    // Journals (chunked, timeout-safe)
    // ---------------------------------------------------------------------

    /** getEntriesJournal (chunked, deduplicated) */
    public function entriesJournalChunked(DateTimeInterface $from, DateTimeInterface $to, int $chunkDays = 7): array
    {
        return $this->getRangeChunked('getEntriesJournal', 'journals', $from, $to, $chunkDays);
    }

    /** getCustomersMoneyJournal (chunked, deduplicated) */
    public function customersMoneyJournalChunked(DateTimeInterface $from, DateTimeInterface $to, int $chunkDays = 14): array
    {
        return $this->getRangeChunked('getCustomersMoneyJournal', 'journals', $from, $to, $chunkDays);
    }

    /** getVendorsMoneyJournal (chunked, deduplicated) */
    public function vendorsMoneyJournalChunked(DateTimeInterface $from, DateTimeInterface $to, int $chunkDays = 14): array
    {
        return $this->getRangeChunked('getVendorsMoneyJournal', 'journals', $from, $to, $chunkDays);
    }

    // ---------------------------------------------------------------------
    // Reports (raw)
    // ---------------------------------------------------------------------

    /** getCustomersCycleReport (raw) */
    public function customersCycleReport(DateTimeInterface $from, DateTimeInterface $to): array
    {
        return $this->getRange('getCustomersCycleReport', $from, $to);
    }

    /** getVendorsCycleReport (raw) */
    public function vendorsCycleReport(DateTimeInterface $from, DateTimeInterface $to): array
    {
        return $this->getRange('getVendorsCycleReport', $from, $to);
    }

    /** getProductsLastInReport (raw) */
    public function productsLastInReport(DateTimeInterface $from, DateTimeInterface $to): array
    {
        return $this->getRange('getProductsLastInReport', $from, $to);
    }

    /** getProductsInReturnReport (raw) */
    public function productsInReturnReport(DateTimeInterface $from, DateTimeInterface $to): array
    {
        return $this->getRange('getProductsInReturnReport', $from, $to);
    }

    // ---------------------------------------------------------------------
    // Typed: Cycle Reports
    // ---------------------------------------------------------------------

    public function customersCycleReportTyped(DateTimeInterface $from, DateTimeInterface $to): CycleReportResponseDto
    {
        return CycleReportResponseDto::fromArray(
            $this->getRange('getCustomersCycleReport', $from, $to)
        );
    }

    public function vendorsCycleReportTyped(DateTimeInterface $from, DateTimeInterface $to): CycleReportResponseDto
    {
        return CycleReportResponseDto::fromArray(
            $this->getRange('getVendorsCycleReport', $from, $to)
        );
    }

    // ---------------------------------------------------------------------
    // Typed: Money Journals
    // ---------------------------------------------------------------------

    public function customersMoneyJournalTyped(DateTimeInterface $from, DateTimeInterface $to): MoneyJournalResponseDto
    {
        return MoneyJournalResponseDto::fromArray(
            $this->getRange('getCustomersMoneyJournal', $from, $to)
        );
    }

    public function vendorsMoneyJournalTyped(DateTimeInterface $from, DateTimeInterface $to): MoneyJournalResponseDto
    {
        return MoneyJournalResponseDto::fromArray(
            $this->getRange('getVendorsMoneyJournal', $from, $to)
        );
    }

    public function customersMoneyJournalChunkedTyped(DateTimeInterface $from, DateTimeInterface $to, int $chunkDays = 14): MoneyJournalResponseDto
    {
        return MoneyJournalResponseDto::fromArray(
            $this->getRangeChunked('getCustomersMoneyJournal', 'journals', $from, $to, $chunkDays)
        );
    }

    public function vendorsMoneyJournalChunkedTyped(DateTimeInterface $from, DateTimeInterface $to, int $chunkDays = 14): MoneyJournalResponseDto
    {
        return MoneyJournalResponseDto::fromArray(
            $this->getRangeChunked('getVendorsMoneyJournal', 'journals', $from, $to, $chunkDays)
        );
    }

    public function entriesJournalTyped(DateTimeInterface $from, DateTimeInterface $to): EntriesJournalResponseDto
    {
        return EntriesJournalResponseDto::fromArray(
            $this->getRange('getEntriesJournal', $from, $to)
        );
    }

    public function entriesJournalChunkedTyped(DateTimeInterface $from, DateTimeInterface $to, int $chunkDays = 7): EntriesJournalResponseDto
    {
        return EntriesJournalResponseDto::fromArray(
            $this->getRangeChunked('getEntriesJournal', 'journals', $from, $to, $chunkDays)
        );
    }

    public function productsLastInReportTyped(DateTimeInterface $from, DateTimeInterface $to): ProductsLastInReportResponseDto
    {
        return ProductsLastInReportResponseDto::fromArray(
            $this->getRange('getProductsLastInReport', $from, $to)
        );
    }

    public function productsInReturnReportTyped(DateTimeInterface $from, DateTimeInterface $to): ProductsInReturnReportResponseDto
    {
        return ProductsInReturnReportResponseDto::fromArray(
            $this->getRange('getProductsInReturnReport', $from, $to)
        );
    }

    public function customersOrderJournalTyped(DateTimeInterface $from, DateTimeInterface $to): CustomersOrderJournalResponseDto
    {
        return CustomersOrderJournalResponseDto::fromArray(
            $this->getRange('getCustomersOrderJournal', $from, $to)
        );
    }

    public function customersOrderJournalChunkedTyped(DateTimeInterface $from, DateTimeInterface $to, int $chunkDays = 14): CustomersOrderJournalResponseDto
    {
        return CustomersOrderJournalResponseDto::fromArray(
            $this->getRangeChunked('getCustomersOrderJournal', 'journals', $from, $to, $chunkDays)
        );
    }

    public function customersReturnJournalTyped(DateTimeInterface $from, DateTimeInterface $to): CustomersReturnJournalResponseDto
    {
        return CustomersReturnJournalResponseDto::fromArray(
            $this->getRange('getCustomersReturnJournal', $from, $to)
        );
    }

    public function customersReturnJournalChunkedTyped(DateTimeInterface $from, DateTimeInterface $to, int $chunkDays = 14): CustomersReturnJournalResponseDto
    {
        return CustomersReturnJournalResponseDto::fromArray(
            $this->getRangeChunked('getCustomersReturnJournal', 'journals', $from, $to, $chunkDays)
        );
    }

    public function productionsJournalTyped(DateTimeInterface $from, DateTimeInterface $to): ProductionsJournalResponseDto
    {
        return ProductionsJournalResponseDto::fromArray(
            $this->getRange('getProductionsJournal', $from, $to)
        );
    }

    public function productionsJournalChunkedTyped(DateTimeInterface $from, DateTimeInterface $to, int $chunkDays = 14): ProductionsJournalResponseDto
    {
        return ProductionsJournalResponseDto::fromArray(
            $this->getRangeChunked('getProductionsJournal', 'journals', $from, $to, $chunkDays)
        );
    }

    public function discountCardsJournalTyped(DateTimeInterface $from, DateTimeInterface $to): DiscountCardsJournalResponseDto
    {
        return DiscountCardsJournalResponseDto::fromArray(
            $this->getRange('getDiscountCardsJournal', $from, $to)
        );
    }

    public function discountCardsJournalChunkedTyped(DateTimeInterface $from, DateTimeInterface $to, int $chunkDays = 14): DiscountCardsJournalResponseDto
    {
        return DiscountCardsJournalResponseDto::fromArray(
            $this->getRangeChunked('getDiscountCardsJournal', 'journals', $from, $to, $chunkDays)
        );
    }

    public function providedServicesJournalTyped(DateTimeInterface $from, DateTimeInterface $to): ProvidedServicesJournalResponseDto
    {
        return ProvidedServicesJournalResponseDto::fromArray(
            $this->getRange('getDocProvidedServicesJournal', $from, $to)
        );
    }

    public function providedServicesJournalChunkedTyped(DateTimeInterface $from, DateTimeInterface $to, int $chunkDays = 14): ProvidedServicesJournalResponseDto
    {
        return ProvidedServicesJournalResponseDto::fromArray(
            $this->getRangeChunked('getDocProvidedServicesJournal', 'journals', $from, $to, $chunkDays)
        );
    }

    public function receivedServicesJournalTyped(DateTimeInterface $from, DateTimeInterface $to): ReceivedServicesJournalResponseDto
    {
        return ReceivedServicesJournalResponseDto::fromArray(
            $this->getRange('getDocReceivedServicesJournal', $from, $to)
        );
    }

    public function receivedServicesJournalChunkedTyped(DateTimeInterface $from, DateTimeInterface $to, int $chunkDays = 14): ReceivedServicesJournalResponseDto
    {
        return ReceivedServicesJournalResponseDto::fromArray(
            $this->getRangeChunked('getDocReceivedServicesJournal', 'journals', $from, $to, $chunkDays)
        );
    }
}
