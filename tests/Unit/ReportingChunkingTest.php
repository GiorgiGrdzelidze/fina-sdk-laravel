<?php

declare(strict_types=1);

namespace Fina\Sdk\Laravel\Tests\Unit;

use Carbon\CarbonImmutable;
use Fina\Sdk\Laravel\Client\FinaClient;
use Fina\Sdk\Laravel\Tests\TestCase;
use Illuminate\Support\Facades\Http;

final class ReportingChunkingTest extends TestCase
{
    private function makeClient(): FinaClient
    {
        return $this->app->make(FinaClient::class);
    }

    public function test_chunked_merges_and_deduplicates_by_id_version(): void
    {
        // Two chunks with overlapping items (id:1/v:a appears in both)
        $chunk1 = [
            'journals' => [
                ['id' => 1, 'version' => 'a', 'amount' => 100],
                ['id' => 2, 'version' => 'b', 'amount' => 200],
            ],
            'ex' => null,
        ];

        $chunk2 = [
            'journals' => [
                ['id' => 1, 'version' => 'a', 'amount' => 100], // duplicate
                ['id' => 3, 'version' => 'c', 'amount' => 300],
            ],
            'ex' => null,
        ];

        $callIndex = 0;

        Http::fake(function ($request) use (&$callIndex, $chunk1, $chunk2) {
            if (str_contains($request->url(), '/api/authentication/authenticate')) {
                return Http::response(['token' => 'tok', 'ex' => null], 200);
            }

            if (str_contains($request->url(), 'getEntriesJournal')) {
                $callIndex++;

                return Http::response($callIndex === 1 ? $chunk1 : $chunk2, 200);
            }

            return Http::response([], 404);
        });

        $client = $this->makeClient();

        // 14-day range with 7-day chunks = 2 chunks
        $from = CarbonImmutable::create(2025, 1, 1);
        $to = CarbonImmutable::create(2025, 1, 14);

        $result = $client->reporting()->entriesJournalChunked($from, $to, 7);

        // Should have 3 unique items (deduped id:1/v:a)
        $this->assertCount(3, $result['journals']);
        $this->assertNull($result['ex']);

        // Verify the items
        $ids = array_column($result['journals'], 'id');
        $this->assertSame([1, 2, 3], $ids);
    }

    public function test_chunked_preserves_collection_key_and_ex(): void
    {
        Http::fake(function ($request) {
            if (str_contains($request->url(), '/api/authentication/authenticate')) {
                return Http::response(['token' => 'tok', 'ex' => null], 200);
            }

            if (str_contains($request->url(), 'getEntriesJournal')) {
                return Http::response([
                    'journals' => [
                        ['id' => 1, 'version' => 'x', 'amount' => 50],
                    ],
                    'ex' => null,
                ], 200);
            }

            return Http::response([], 404);
        });

        $client = $this->makeClient();
        $from = CarbonImmutable::create(2025, 1, 1);
        $to = CarbonImmutable::create(2025, 1, 5);

        $result = $client->reporting()->entriesJournalChunked($from, $to, 7);

        $this->assertArrayHasKey('journals', $result);
        $this->assertArrayHasKey('ex', $result);
        $this->assertCount(1, $result['journals']);
    }

    public function test_chunked_typed_returns_dto(): void
    {
        Http::fake(function ($request) {
            if (str_contains($request->url(), '/api/authentication/authenticate')) {
                return Http::response(['token' => 'tok', 'ex' => null], 200);
            }

            if (str_contains($request->url(), 'getEntriesJournal')) {
                return Http::response([
                    'journals' => [
                        ['id' => 10, 'version' => 'v1', 'date' => '2025-01-01', 'doc_num' => 'D1', 'doc_type' => 5, 'purpose' => 'test', 'amount' => 99.9],
                    ],
                    'ex' => null,
                ], 200);
            }

            return Http::response([], 404);
        });

        $client = $this->makeClient();
        $from = CarbonImmutable::create(2025, 1, 1);
        $to = CarbonImmutable::create(2025, 1, 5);

        $dto = $client->reporting()->entriesJournalChunkedTyped($from, $to, 7);

        $this->assertInstanceOf(\Fina\Sdk\Laravel\Reporting\Dto\EntriesJournalResponseDto::class, $dto);
        $this->assertCount(1, $dto->journals);
        $this->assertSame(10, $dto->journals[0]->id);
        $this->assertSame('v1', $dto->journals[0]->version);
        $this->assertNull($dto->ex);
    }

    public function test_dedupe_falls_back_to_id_only(): void
    {
        $chunk1 = [
            'reports' => [
                ['id' => 1, 'value' => 'first'],
                ['id' => 2, 'value' => 'second'],
            ],
            'ex' => null,
        ];

        $chunk2 = [
            'reports' => [
                ['id' => 1, 'value' => 'first-dup'], // same id, no version
                ['id' => 3, 'value' => 'third'],
            ],
            'ex' => null,
        ];

        $callIndex = 0;

        Http::fake(function ($request) use (&$callIndex, $chunk1, $chunk2) {
            if (str_contains($request->url(), '/api/authentication/authenticate')) {
                return Http::response(['token' => 'tok', 'ex' => null], 200);
            }

            if (str_contains($request->url(), 'getCustomersCycleReport')) {
                $callIndex++;

                return Http::response($callIndex === 1 ? $chunk1 : $chunk2, 200);
            }

            return Http::response([], 404);
        });

        $client = $this->makeClient();
        $from = CarbonImmutable::create(2025, 1, 1);
        $to = CarbonImmutable::create(2025, 1, 14);

        $result = $client->reporting()->getRangeChunked(
            'getCustomersCycleReport',
            'reports',
            $from,
            $to,
            7
        );

        // id:1 should be deduped (first occurrence kept)
        $this->assertCount(3, $result['reports']);
        $this->assertSame('first', $result['reports'][0]['value']);
    }
}
