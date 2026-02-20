# Changelog

All notable changes to the **FINA SDK for Laravel** package will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.1.0] - 2026-02-20

### Added

#### Core
- `FinaClient` — central HTTP client with configurable timeout, retry, and base URL
- `AuthService` + `TokenStore` — automatic token fetch, caching (~35 h TTL), and 401 auto-refresh
- Laravel service provider with package discovery and `'fina'` alias
- Publishable `config/fina.php` with environment variable support

#### Operation API Clients
- `CustomersApi` — all, getByCode, groups, addresses, additionalFields
- `VendorsApi` — all, getByCode, groups, addresses, additionalFields
- `ProductsApi` — all, groups, webGroups, byIds, after, prices, pricesAfter, units, characteristics, images, barcodes, rest
- `DocumentsApi` — 17 save methods + 6 get methods with generic `save()` / `getDoc()` fallback
- `LoyaltyApi` — bonusCoeff, cardsByHolder, saveBonusOperation
- `ReferenceApi` — stores, projects, terminals, cashes, creditBanks, priceTypes, users, userPermissions, bankAccounts, staffGroups, staffs, giftCards, discountTypes, units, docTypes (with caching)

#### Reporting API Clients
- 10 journal types: Entries, CustomersOrder, CustomersReturn, CustomersMoney, VendorsMoney, Productions, DiscountCards, ProvidedServices, ReceivedServices, Realizes
- 4 report types: CustomersCycle, VendorsCycle, ProductsLastIn, ProductsInReturn
- Raw, typed, and chunked-typed variants for all supported journals
- `getRangeChunked()` with configurable window size and automatic deduplication

#### DTOs
- 17 typed request payload DTOs with `toArray()` serialisation
- `ValidatesPayload` contract with automatic Laravel validation before API calls
- Typed response DTOs with `fromArray()` mapping for all reporting endpoints
- Reference data DTOs: DocTypeDto, UserDto, UserPermissionsDto, StaffDto, StaffGroupDto, BankAccountDto, DiscountTypeDto, GiftCardDto, UnitDto

#### Exceptions
- `FinaException` base class (RuntimeException)
- `FinaConfigException` — missing configuration
- `FinaHttpException` — HTTP failures with `$status` and `$body`
- `FinaRemoteException` — FINA `ex` field errors
- `FinaValidationException` — payload validation failures with `$errors`

#### Quality
- 35 unit tests (PHPUnit + Orchestra Testbench, `Http::fake` only, no DB)
- PHPStan level 6 static analysis
- Laravel Pint code style enforcement
- GitHub Actions CI (PHP 8.2 / 8.3 / 8.4 matrix)
- Comprehensive PHPDoc on all classes and methods

#### Documentation
- README.md with installation, configuration, usage, API reference, troubleshooting
- CHANGELOG.md, CONTRIBUTING.md, SECURITY.md
- MIT License
