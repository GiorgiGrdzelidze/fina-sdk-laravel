<?php

declare(strict_types=1);

namespace Fina\Sdk\Laravel\Tests\Unit;

use Carbon\CarbonImmutable;
use Fina\Sdk\Laravel\Client\FinaClient;
use Fina\Sdk\Laravel\Tests\TestCase;
use Illuminate\Support\Facades\Http;

final class ReportingUrlTest extends TestCase
{
    private function makeClient(): FinaClient
    {
        return $this->app->make(FinaClient::class);
    }

    public function test_reporting_range_uses_correct_date_format(): void
    {
        Http::fake([
            '*/api/authentication/authenticate' => Http::response(['token' => 'tok', 'ex' => null], 200),
            '*/api/reporting/*' => Http::response(['journals' => [], 'ex' => null], 200),
        ]);

        $client = $this->makeClient();
        $from = CarbonImmutable::create(2025, 3, 15, 10, 30, 0);
        $to = CarbonImmutable::create(2025, 3, 20, 23, 59, 59);

        $client->reporting()->entriesJournal($from, $to);

        Http::assertSent(function ($request) {
            if (! str_contains($request->url(), 'getEntriesJournal')) {
                return false;
            }

            $url = $request->url();

            // Must contain properly formatted dates
            $this->assertStringContainsString('2025-03-15T10%3A30%3A00', $url);
            $this->assertStringContainsString('2025-03-20T23%3A59%3A59', $url);

            return true;
        });
    }

    public function test_reporting_path_structure(): void
    {
        Http::fake([
            '*/api/authentication/authenticate' => Http::response(['token' => 'tok', 'ex' => null], 200),
            '*/api/reporting/*' => Http::response(['reports' => [], 'ex' => null], 200),
        ]);

        $client = $this->makeClient();
        $from = CarbonImmutable::create(2025, 1, 1, 0, 0, 0);
        $to = CarbonImmutable::create(2025, 1, 31, 23, 59, 59);

        $client->reporting()->customersCycleReport($from, $to);

        Http::assertSent(function ($request) {
            if (! str_contains($request->url(), 'getCustomersCycleReport')) {
                return false;
            }

            $url = $request->url();

            // Path must follow: /api/reporting/{method}/{from}/{to}
            $this->assertMatchesRegularExpression(
                '#/api/reporting/getCustomersCycleReport/[^/]+/[^/]+#',
                $url
            );

            return true;
        });
    }

    public function test_journals_api_uses_correct_date_format(): void
    {
        Http::fake([
            '*/api/authentication/authenticate' => Http::response(['token' => 'tok', 'ex' => null], 200),
            '*/api/reporting/*' => Http::response(['journals' => [], 'ex' => null], 200),
        ]);

        $client = $this->makeClient();
        $from = CarbonImmutable::create(2025, 6, 1, 0, 0, 0);
        $to = CarbonImmutable::create(2025, 6, 30, 23, 59, 59);

        $client->journals()->entries($from, $to);

        Http::assertSent(function ($request) {
            if (! str_contains($request->url(), 'getEntriesJournal')) {
                return false;
            }

            $this->assertStringContainsString('2025-06-01T00%3A00%3A00', $request->url());
            $this->assertStringContainsString('2025-06-30T23%3A59%3A59', $request->url());

            return true;
        });
    }
}
