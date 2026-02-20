# FINA SDK for Laravel

[![CI](https://github.com/GiorgiGrdzelidze/fina-sdk-laravel/actions/workflows/ci.yml/badge.svg)](https://github.com/GiorgiGrdzelidze/fina-sdk-laravel/actions/workflows/ci.yml)
[![Latest Version](https://img.shields.io/github/v/release/GiorgiGrdzelidze/fina-sdk-laravel?label=release)](https://github.com/GiorgiGrdzelidze/fina-sdk-laravel/releases)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.2-8892BF)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/laravel-%3E%3D12.0-FF2D20)](https://laravel.com)
[![License: MIT](https://img.shields.io/badge/license-MIT-green)](LICENSE)

A Laravel SDK for the **FINA Web API (v6.0)** — providing authenticated HTTP transport with token caching and 401 auto-refresh, Operation API clients (Customers, Vendors, Products, Documents, Loyalty, Reference), Reporting API clients with chunked retrieval and automatic deduplication, typed DTOs with built-in Laravel validation, and a full quality toolchain (PHPUnit, PHPStan, Pint, GitHub Actions CI).

---

## Features

- **Authentication** — automatic token fetch, cache (~35 h TTL), and transparent refresh on 401
- **HTTP client** — configurable timeout, retry count, and retry delay via Laravel's HTTP facade
- **Operation API clients** — Reference, Documents, Products, Customers, Vendors, Loyalty
- **Reporting API clients** — 10 journal types + 4 report types, each with raw and typed variants
- **Chunked reporting** — splits large date ranges into configurable windows, merges results, deduplicates by `id+version`
- **Typed request DTOs** — 17 document payload classes with `toArray()` serialisation
- **Payload validation** — DTOs implement `ValidatesPayload`; validated automatically before API calls
- **Typed response DTOs** — `fromArray()` mapping for all reporting endpoints with forward-compatible raw-payload retention
- **Structured exceptions** — `FinaConfigException`, `FinaHttpException`, `FinaRemoteException`, `FinaValidationException`
- **Quality** — PHPUnit + Orchestra Testbench (`Http::fake` only, no DB), PHPStan level 6, Laravel Pint, GitHub Actions CI (PHP 8.2 / 8.3 / 8.4)

---

## Requirements

- **PHP** ≥ 8.2
- **Laravel** ≥ 12.0
- A running FINA Web API (v6.0) instance with valid credentials

---

## Installation

### Via Packagist

```bash
composer require fina/fina-sdk-laravel
```

Laravel's package discovery registers the service provider automatically — no manual registration needed.

### Local development (path repository)

If the package lives under `packages/fina/fina-sdk-laravel` in your project, add a path repository to your **host app's** `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "packages/fina/fina-sdk-laravel"
        }
    ]
}
```

Then require it:

```bash
composer require fina/fina-sdk-laravel:*
```

---

## Configuration

### Publish the config (optional)

```bash
php artisan vendor:publish --tag=fina-config
```

This creates `config/fina.php` where you can customise all settings.

### Environment variables

Add the following to your `.env` file:

```dotenv
FINA_BASE_URL=https://your-fina-host:5007
FINA_LOGIN=your_login
FINA_PASSWORD=your_password

# Optional overrides
FINA_TIMEOUT=120
FINA_RETRY_TIMES=2
FINA_RETRY_SLEEP_MS=300
FINA_TOKEN_TTL=126000
FINA_DOC_TYPES_TTL=3600
```

| Variable | Default | Description |
|---|---|---|
| `FINA_BASE_URL` | *(required)* | Base URL of your FINA instance (scheme + host + port) |
| `FINA_LOGIN` | *(required)* | API login |
| `FINA_PASSWORD` | *(required)* | API password |
| `FINA_TIMEOUT` | `120` | HTTP timeout in seconds |
| `FINA_RETRY_TIMES` | `2` | Number of HTTP retries on transient failure |
| `FINA_RETRY_SLEEP_MS` | `300` | Delay between retries in milliseconds |
| `FINA_TOKEN_TTL` | `126000` | Token cache lifetime in seconds (~35 hours) |
| `FINA_TOKEN_CACHE_PREFIX` | `fina:sdk:token:` | Cache key prefix for the auth token |
| `FINA_DOC_TYPES_TTL` | `3600` | Doc-types cache lifetime in seconds |
| `FINA_CACHE_PREFIX` | `fina-sdk` | General cache key prefix |

### API prefixes

The default prefixes match the standard FINA Web API layout and rarely need changing:

```php
'prefixes' => [
    'authentication' => '/api/authentication',
    'operation'      => '/api/operation',
    'reporting'      => '/api/reporting',
],
```

### Full config/fina.php

```php
return [
    'base_url'               => env('FINA_BASE_URL'),
    'login'                  => env('FINA_LOGIN'),
    'password'               => env('FINA_PASSWORD'),

    'timeout'                => (int) env('FINA_TIMEOUT', 120),
    'retry_times'            => (int) env('FINA_RETRY_TIMES', 2),
    'retry_sleep_ms'         => (int) env('FINA_RETRY_SLEEP_MS', 300),

    'token_cache_ttl_seconds' => (int) env('FINA_TOKEN_TTL', 35 * 3600),
    'token_cache_key_prefix'  => env('FINA_TOKEN_CACHE_PREFIX', 'fina:sdk:token:'),

    'prefixes' => [
        'authentication' => '/api/authentication',
        'operation'      => '/api/operation',
        'reporting'      => '/api/reporting',
    ],

    'cache' => [
        'doc_types_ttl' => (int) env('FINA_DOC_TYPES_TTL', 3600),
        'prefix'        => env('FINA_CACHE_PREFIX', 'fina-sdk'),
    ],
];
```

---

## Usage

### Resolving the client

```php
use Fina\Sdk\Laravel\Client\FinaClient;

// Via alias
$fina = app('fina');

// Via dependency injection
public function __construct(private FinaClient $fina) {}
```

### Reference data

```php
$stores   = $fina->reference()->stores();
$docTypes = $fina->reference()->docTypesCached();  // cached for 1 hour
$users    = $fina->reference()->users();            // UserDto[]
```

### Saving a document with DTO validation

```php
use Fina\Sdk\Laravel\Operation\Dto\ProductOutPayload;
use Fina\Sdk\Laravel\Operation\Dto\ProductLine;

$payload = new ProductOutPayload(
    id: 0, date: now(), numPrefix: '', num: 0,
    purpose: 'Sale via API', amount: 100.0,
    currency: 'GEL', rate: 1.0,
    store: 1, user: 1, staff: 1, project: 0, customer: 8,
    isVat: true, makeEntry: true,
    payType: 1, wType: 0, tType: 1, tPayer: 2, wCost: 0, foreign: false,
    products: [new ProductLine(id: 2, subId: 0, quantity: 3, price: 33.33)],
);

$response = $fina->documents()->saveProductOut($payload);
// $response->id  — saved document ID
// $response->ex  — null on success
```

### Reporting — raw and typed

```php
$from = now()->subMonth();
$to   = now();

// Raw array
$raw = $fina->reporting()->entriesJournal($from, $to);

// Typed DTO
$dto = $fina->reporting()->entriesJournalTyped($from, $to);
// $dto->journals — EntriesJournalRowDto[]
// $dto->ex       — null on success
```

### Chunked reporting for large date ranges

```php
$from = now()->subYear();
$to   = now();

$dto = $fina->reporting()->entriesJournalChunkedTyped($from, $to, chunkDays: 7);
// Splits into 7-day windows, merges, deduplicates by id+version
```

### Generic range call

```php
$fina->reporting()->getRange('getEntriesJournal', $from, $to);

$fina->reporting()->getRangeChunked(
    method: 'getEntriesJournal',
    collectionKey: 'journals',
    from: $from,
    to: $to,
    chunkDays: 7,
    dedupeKeyFn: fn (array $item) => $item['id'] . ':' . $item['version'],
);
```

---

## API Overview

### Operation endpoints

| Client | Method | FINA endpoint |
|---|---|---|
| `customers()` | `all()` | `getCustomers` |
| | `getByCode($code)` | `getCustomersByCode/{code}` |
| | `groups()` | `getCustomerGroups` |
| | `addresses($id)` | `getCustomerAddresses/{id}` |
| | `additionalFields()` | `getCustomerAdditionalFields` |
| `vendors()` | `all()` | `getVendors` |
| | `getByCode($code)` | `getVendorsByCode/{code}` |
| | `groups()` | `getVendorGroups` |
| | `addresses($id)` | `getVendorAddresses/{id}` |
| | `additionalFields()` | `getVendorAdditionalFields` |
| `products()` | `all()` | `getProducts` |
| | `groups()` | `getProductGroups` |
| | `webGroups()` | `getWebProductGroups` |
| | `byIds($ids)` | `getProductsArray` |
| | `after($date)` | `getProductsAfter/{date}` |
| | `prices()` | `getProductPrices` |
| | `pricesAfter($date)` | `getProductPricesAfter/{date}` |
| | `units()` | `getProductUnits` |
| | `characteristics()` | `getCharacteristics` |
| | `imagesByProductIds($ids)` | `getProductsImageArray` |
| | `barcodesByProductIds($ids)` | `getProductsBarcodeArray` |
| | `restByProductIds($ids)` | `getProductsRestArray` |
| `reference()` | `stores()` | `getStores` |
| | `projects()` | `getProjects` |
| | `terminals()` | `getTerminals` |
| | `cashes()` | `getCashes` |
| | `creditBanks()` | `getCreditBanks` |
| | `priceTypes()` | `getPriceTypes` |
| | `users()` → `UserDto[]` | `getUsers` |
| | `userPermissions($id)` → `UserPermissionsDto` | `getUserPermissions/{id}` |
| | `bankAccounts()` → `BankAccountDto[]` | `getBankAccounts` |
| | `staffGroups()` → `StaffGroupDto[]` | `getStaffGroups` |
| | `staffs()` → `StaffDto[]` | `getStaffs` |
| | `giftCards()` → `GiftCardDto[]` | `getGiftCards` |
| | `discountTypes()` → `DiscountTypeDto[]` | `getDiscountTypes` |
| | `units()` → `UnitDto[]` | `getUnits` |
| | `docTypes()` → `DocTypeDto[]` | `getDocTypes` |
| | `docTypesCached()` | *(cached)* |
| | `supportedDocTypes()` | *(filtered)* |
| | `findDocType($id)` | *(lookup)* |
| `loyalty()` | `bonusCoeff()` → `BonusCoeffResponse` | `getBonusCoeff` |
| | `cardsByHolder($code)` | `getLoyaltyCardsByHolder/{code}` |
| | `saveBonusOperation($p)` → `BonusOperationResponse` | `saveDocBonusOperation` |

### Document save/get endpoints

| Method | FINA endpoint | DTO |
|---|---|---|
| `saveCustomerOrder($p)` | `saveDocCustomerOrder` | `CustomerOrderPayload` |
| `saveProductOut($p)` | `saveDocProductOut` | `ProductOutPayload` |
| `saveProductIn($p)` | `saveDocProductIn` | `ProductInPayload` |
| `saveProductMove($p)` | `saveDocProductMove` | `ProductMovePayload` |
| `saveProductCancel($p)` | `saveDocProductCancel` | `ProductCancelPayload` |
| `saveProvidedService($p)` | `saveDocProvidedService` | `ProvidedServicePayload` |
| `saveCustomerReturn($p)` | `saveDocCustomerReturn` | `CustomerReturnPayload` |
| `saveCafeOrder($p)` | `saveDocCafeOrder` | `CafeOrderPayload` |
| `saveCustomerMoneyIn($p)` | `saveDocCustomerMoneyIn` | `CustomerMoneyInPayload` |
| `saveCustomerAdvanceIn($p)` | `saveDocCustomerAdvanceIn` | *(raw array)* |
| `saveCustomerMoneyReturn($p)` | `saveDocCustomerMoneyReturn` | `CustomerMoneyReturnPayload` |
| `saveBonusPayment($p)` | `saveDocBonusPayment` | `BonusPaymentPayload` |
| `saveCustomerMoneyOut($p)` | `saveDocCustomerMoneyOut` | `CustomerMoneyOutPayload` |
| `saveVendorMoneyIn($p)` | `saveDocVendorMoneyIn` | `VendorMoneyInPayload` |
| `saveVendorMoneyOut($p)` | `saveDocVendorMoneyOut` | `VendorMoneyOutPayload` |
| `saveVendorMoneyReturn($p)` | `saveDocVendorMoneyReturn` | `VendorMoneyReturnPayload` |
| `saveProduction($p)` | `saveDocProduction` | `ProductionPayload` |
| `getCustomerOrder($id)` | `getDocCustomerOrder/{id}` | — |
| `getProductOut($id)` | `getDocProductOut/{id}` | — |
| `getProductMove($id)` | `getDocProductMove/{id}` | — |
| `getReceivedService($id)` | `getDocReceivedService/{id}` | — |
| `getCustomerReturn($id)` | `getDocCustomerReturn/{id}` | — |
| `getProduction($id)` | `getDocProduction/{id}` | — |
| `getProductionTyped($id)` | `getDocProduction/{id}` | `ProductionDocDto` |

### Reporting journals (raw + typed + chunked)

| Journal | Raw | Typed | Chunked Typed | Response DTO |
|---|---|---|---|---|
| Entries | `entriesJournal()` | `entriesJournalTyped()` | `entriesJournalChunkedTyped()` | `EntriesJournalResponseDto` |
| Customers Orders | `customersOrderJournal()` | `customersOrderJournalTyped()` | `customersOrderJournalChunkedTyped()` | `CustomersOrderJournalResponseDto` |
| Customers Returns | `customersReturnJournal()` | `customersReturnJournalTyped()` | `customersReturnJournalChunkedTyped()` | `CustomersReturnJournalResponseDto` |
| Customers Money | `customersMoneyJournal()` | `customersMoneyJournalTyped()` | `customersMoneyJournalChunkedTyped()` | `MoneyJournalResponseDto` |
| Vendors Money | `vendorsMoneyJournal()` | `vendorsMoneyJournalTyped()` | `vendorsMoneyJournalChunkedTyped()` | `MoneyJournalResponseDto` |
| Productions | `productionsJournal()` | `productionsJournalTyped()` | `productionsJournalChunkedTyped()` | `ProductionsJournalResponseDto` |
| Discount Cards | `discountCardsJournal()` | `discountCardsJournalTyped()` | `discountCardsJournalChunkedTyped()` | `DiscountCardsJournalResponseDto` |
| Provided Services | `providedServicesJournal()` | `providedServicesJournalTyped()` | `providedServicesJournalChunkedTyped()` | `ProvidedServicesJournalResponseDto` |
| Received Services | `receivedServicesJournal()` | `receivedServicesJournalTyped()` | `receivedServicesJournalChunkedTyped()` | `ReceivedServicesJournalResponseDto` |
| Realizes | `realizesJournal()` | — | — | — |

### Reporting reports (raw + typed)

| Report | Raw | Typed | Response DTO |
|---|---|---|---|
| Customers Cycle | `customersCycleReport()` | `customersCycleReportTyped()` | `CycleReportResponseDto` |
| Vendors Cycle | `vendorsCycleReport()` | `vendorsCycleReportTyped()` | `CycleReportResponseDto` |
| Products Last-In | `productsLastInReport()` | `productsLastInReportTyped()` | `ProductsLastInReportResponseDto` |
| Products In/Return | `productsInReturnReport()` | `productsInReturnReportTyped()` | `ProductsInReturnReportResponseDto` |

---

## DTOs & Validation

### How it works

Request DTOs implement two contracts:

- **`ArrayPayload`** — provides `toArray()` for serialisation
- **`ValidatesPayload`** — extends `ArrayPayload` and adds `rules()`, `messages()`, and `attributes()` for Laravel validation

When you pass a `ValidatesPayload` DTO to any `save*` method, the SDK validates it automatically before sending the HTTP request. If validation fails, a `FinaValidationException` is thrown immediately — no request is made.

### Example

```php
use Fina\Sdk\Laravel\Operation\Dto\BonusOperationPayload;
use Fina\Sdk\Laravel\Support\PayloadValidator;
use Fina\Sdk\Laravel\Exceptions\FinaValidationException;

$payload = new BonusOperationPayload(
    cardId: 1,
    refId: 100,
    coeff: 1,
    amount: 50.0,
);

// Manual validation (optional — save methods do this automatically)
PayloadValidator::validate($payload); // throws FinaValidationException on failure

// Serialise to array
$array = $payload->toArray();
// ['card_id' => 1, 'ref_id' => 100, 'coeff' => 1, 'amount' => 50.0]
```

### Validation failure

```php
try {
    $fina->documents()->saveProductOut($invalidPayload);
} catch (FinaValidationException $e) {
    // $e->errors — array<string, list<string>> keyed by field name
    // $e->getMessage() — 'FINA SDK payload validation failed'
    return response()->json(['errors' => $e->errors], 422);
}
```

### Response DTOs and forward compatibility

All typed response DTOs preserve the original array from the API. If FINA adds new fields, they are accessible from the raw data even before the SDK adds a typed property.

---

## Error Handling

### Exception hierarchy

All exceptions extend `FinaException` (`RuntimeException`):

| Exception | When | Key properties |
|---|---|---|
| `FinaConfigException` | Missing `base_url`, `login`, or `password` | — |
| `FinaHttpException` | HTTP 4xx/5xx (after retry exhaustion) | `$status`, `$body` |
| `FinaRemoteException` | FINA returned non-null `ex` in JSON response | `$ex` |
| `FinaValidationException` | DTO payload failed Laravel validation | `$errors` |

### Example

```php
use Fina\Sdk\Laravel\Exceptions\FinaException;
use Fina\Sdk\Laravel\Exceptions\FinaHttpException;
use Fina\Sdk\Laravel\Exceptions\FinaRemoteException;
use Fina\Sdk\Laravel\Exceptions\FinaValidationException;

try {
    $fina->documents()->saveProductOut($payload);
} catch (FinaValidationException $e) {
    // Payload did not pass rules — no HTTP request was made
    Log::warning('Validation failed', ['errors' => $e->errors]);
} catch (FinaHttpException $e) {
    // HTTP-level failure
    Log::error("FINA HTTP {$e->status}", ['body' => $e->body]);
} catch (FinaRemoteException $e) {
    // FINA returned an error in the 'ex' field
    Log::error('FINA remote error', ['ex' => $e->ex]);
} catch (FinaException $e) {
    // Catch-all for any SDK error (including config)
    Log::error("FINA error: {$e->getMessage()}");
}
```

### 401 auto-refresh behaviour

When an API call receives a `401 Unauthorized` response, the SDK:

1. Clears the cached token
2. Fetches a fresh token via `POST /api/authentication/authenticate`
3. Retries the original request **once** with the new token

If the retry also fails, a `FinaHttpException` is thrown with the final status code.

---

## Performance Notes

- **Use chunked reporting for ranges > 30 days.** The FINA API may time out on large date ranges. Chunked methods split the range into smaller windows and merge results automatically.
- **Recommended `chunkDays` defaults:** 7 for entries journals (high volume), 14 for money/order journals (lower volume).
- **Deduplication** is automatic. The default strategy uses `id+version` when both fields are present, falls back to `id` only, and finally uses a SHA-1 hash of the full row as a last resort.
- **Doc-types caching:** Use `docTypesCached()` instead of `docTypes()` to avoid repeated API calls for rarely-changing reference data.
- **Token caching:** The auth token is cached for ~35 hours by default. Adjust `FINA_TOKEN_TTL` if your FINA instance uses a different token lifetime.

---

## Testing & Quality

### Philosophy

All tests use `Http::fake()` exclusively. No real HTTP requests are made, no database is required, and no external services are contacted. Tests run entirely in-memory via Orchestra Testbench.

### Commands

```bash
# Run all tests
composer test

# Static analysis (PHPStan level 6)
composer phpstan -- --memory-limit=512M

# Code style check (Laravel Pint)
composer pint -- --test

# Run all three in sequence
composer ci
```

### Test coverage

| Suite | Tests | What it covers |
|---|---|---|
| `ServiceProviderTest` | 4 | Singleton binding, alias, config loading, client accessors |
| `FinaClientHttpTest` | 5 | Token caching, reuse, 401 auto-refresh, HTTP exceptions |
| `ReportingUrlTest` | 3 | Date format (`yyyy-MM-ddTHH:mm:ss`), URL path structure |
| `ReportingChunkingTest` | 4 | Chunk merging, deduplication, typed DTO output, id-only fallback |
| `DtoValidationTest` | 5 | Valid/invalid payloads, error contents, nested validation |
| `DtoMappingTest` | 14 | Response DTO mapping, empty data, roundtrip, regression tests |
| **Total** | **35** | **111 assertions** |

### CI

GitHub Actions runs on every push and PR against `main`:

- **Matrix:** PHP 8.2, 8.3, 8.4
- **Steps:** `composer test` → `composer phpstan` → `composer pint -- --test`
- **No database services.** No external services.

---

## Versioning & Releases

This package follows [Semantic Versioning](https://semver.org/). The initial release is **v0.1.0**.

### Publishing on Packagist

1. Go to **packagist.org** → Submit → enter `https://github.com/GiorgiGrdzelidze/fina-sdk-laravel`
2. Enable auto-update: copy the Packagist webhook URL and add it to GitHub → Settings → Webhooks
3. Future tags will be picked up automatically

### Installing as a consumer

```bash
# Latest stable
composer require fina/fina-sdk-laravel

# Pin to minor
composer require fina/fina-sdk-laravel:^0.1
```

---

## Troubleshooting

### 401 loops / invalid credentials

If every request fails with `FinaHttpException` status 401:

- Verify `FINA_LOGIN` and `FINA_PASSWORD` are correct
- Check that the FINA user account has API access enabled
- Clear the cached token: `app('fina')->auth()->forgetToken()`
- Check your FINA instance logs for authentication errors

### Wrong base URL format

The SDK strips trailing slashes automatically. Ensure `FINA_BASE_URL` includes the scheme and port:

```dotenv
# Correct
FINA_BASE_URL=https://your-fina-host:5007

# Wrong — missing scheme
FINA_BASE_URL=your-fina-host:5007

# Wrong — includes path
FINA_BASE_URL=https://your-fina-host:5007/api
```

### Timeouts on large reporting ranges

If reporting calls time out, switch to chunked methods:

```php
// Instead of this (may time out for 6+ months):
$fina->reporting()->entriesJournal($from, $to);

// Use this:
$fina->reporting()->entriesJournalChunked($from, $to, chunkDays: 7);
```

You can also increase the timeout: `FINA_TIMEOUT=300`.

### SSL / HTTP mixed content

If your FINA instance uses HTTP (not HTTPS), ensure `FINA_BASE_URL` starts with `http://`. The SDK does not force HTTPS. If you need to disable SSL verification for development, configure it in your Laravel HTTP client settings — the SDK uses Laravel's `Http` facade.

### Config exception on boot

If you see `FinaConfigException: FINA base_url is missing` immediately on app boot, ensure your `.env` file contains `FINA_BASE_URL`, `FINA_LOGIN`, and `FINA_PASSWORD`. The SDK validates these when the `FinaClient` singleton is first resolved.

---

## License

This package is open-sourced software licensed under the [MIT License](LICENSE).

---

## Contributing

Contributions are welcome. Please see [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.

For security vulnerabilities, please see [SECURITY.md](SECURITY.md).