<?php

declare(strict_types=1);

/**
 * Operation API client for reference/lookup data endpoints.
 */

namespace Fina\Sdk\Laravel\Operation;

use Fina\Sdk\Laravel\Endpoints\BaseApi;
use Fina\Sdk\Laravel\Operation\Dto\BankAccountDto;
use Fina\Sdk\Laravel\Operation\Dto\DiscountTypeDto;
use Fina\Sdk\Laravel\Operation\Dto\DocTypeDto;
use Fina\Sdk\Laravel\Operation\Dto\GiftCardDto;
use Fina\Sdk\Laravel\Operation\Dto\StaffDto;
use Fina\Sdk\Laravel\Operation\Dto\StaffGroupDto;
use Fina\Sdk\Laravel\Operation\Dto\UnitDto;
use Fina\Sdk\Laravel\Operation\Dto\UserDto;
use Fina\Sdk\Laravel\Operation\Dto\UserPermissionsDto;
use Illuminate\Support\Facades\Cache;

/**
 * Provides methods for retrieving reference/lookup data (stores, users, staff,
 * doc types, bank accounts, gift cards, etc.) from the FINA Operation API.
 *
 * Includes optional caching for doc types.
 */
final class ReferenceApi extends BaseApi
{
    public function __construct(\Fina\Sdk\Laravel\Client\FinaClient $client)
    {
        parent::__construct($client, 'operation');
    }

    // -----------------------
    // Raw reference endpoints
    // -----------------------

    public function stores(): array
    {
        return $this->get('getStores', [], 'operation.getStores returned ex');
    }

    public function projects(): array
    {
        return $this->get('getProjects', [], 'operation.getProjects returned ex');
    }

    public function terminals(): array
    {
        return $this->get('getTerminals', [], 'operation.getTerminals returned ex');
    }

    public function cashes(): array
    {
        return $this->get('getCashes', [], 'operation.getCashes returned ex');
    }

    public function creditBanks(): array
    {
        return $this->get('getCreditBanks', [], 'operation.getCreditBanks returned ex');
    }

    public function priceTypes(): array
    {
        return $this->get('getPriceTypes', [], 'operation.getPriceTypes returned ex');
    }

    // -----------------------
    // Typed reference endpoints
    // -----------------------

    /**
     * GET api/operation/getUsers
     * Response: {users: [{id,name,type}, ...], ex:null}
     *
     * @return UserDto[]
     */
    public function users(): array
    {
        $data = $this->get('getUsers', [], 'operation.getUsers returned ex');

        return array_map(
            fn ($u) => UserDto::fromArray((array) $u),
            (array) ($data['users'] ?? [])
        );
    }

    /**
     * GET api/operation/getUserPermissions/{user}
     * Response: {permissions: {...}, ex:null}
     */
    public function userPermissions(int $userId): UserPermissionsDto
    {
        $data = $this->get('getUserPermissions/'.$userId, [], 'operation.getUserPermissions returned ex');

        return UserPermissionsDto::fromArray($data);
    }

    /**
     * GET api/operation/getBankAccounts
     * Response: {accounts: [...], ex:null}
     *
     * @return BankAccountDto[]
     */
    public function bankAccounts(): array
    {
        $data = $this->get('getBankAccounts', [], 'operation.getBankAccounts returned ex');

        return array_map(
            fn ($a) => BankAccountDto::fromArray((array) $a),
            (array) ($data['accounts'] ?? [])
        );
    }

    /**
     * GET api/operation/getStaffGroups
     * Response: {groups: [...], ex:null}
     *
     * @return StaffGroupDto[]
     */
    public function staffGroups(): array
    {
        $data = $this->get('getStaffGroups', [], 'operation.getStaffGroups returned ex');

        return array_map(
            fn ($g) => StaffGroupDto::fromArray((array) $g),
            (array) ($data['groups'] ?? [])
        );
    }

    /**
     * GET api/operation/getStaffs
     * Response: {staffs: [...], ex:null}
     *
     * @return StaffDto[]
     */
    public function staffs(): array
    {
        $data = $this->get('getStaffs', [], 'operation.getStaffs returned ex');

        return array_map(
            fn ($s) => StaffDto::fromArray((array) $s),
            (array) ($data['staffs'] ?? [])
        );
    }

    /**
     * GET api/operation/getGiftCards
     * Response: {gifts: [...], ex:null}
     *
     * @return GiftCardDto[]
     */
    public function giftCards(): array
    {
        $data = $this->get('getGiftCards', [], 'operation.getGiftCards returned ex');

        return array_map(
            fn ($g) => GiftCardDto::fromArray((array) $g),
            (array) ($data['gifts'] ?? [])
        );
    }

    /**
     * GET api/operation/getDiscountTypes
     * Response: {types: [...], ex:null}
     *
     * @return DiscountTypeDto[]
     */
    public function discountTypes(): array
    {
        $data = $this->get('getDiscountTypes', [], 'operation.getDiscountTypes returned ex');

        return array_map(
            fn ($t) => DiscountTypeDto::fromArray((array) $t),
            (array) ($data['types'] ?? [])
        );
    }

    /**
     * GET api/operation/getUnits
     * Response: {units: [...], ex:null}
     *
     * @return UnitDto[]
     */
    public function units(): array
    {
        $data = $this->get('getUnits', [], 'operation.getUnits returned ex');

        return array_map(
            fn ($u) => UnitDto::fromArray((array) $u),
            (array) ($data['units'] ?? [])
        );
    }

    // -----------------------
    // Doc Types (typed + helpers + cache)
    // -----------------------

    /**
     * GET api/operation/getDocTypes
     * Response: {types: [...], ex:null}
     *
     * @return DocTypeDto[]
     */
    public function docTypes(): array
    {
        $data = $this->get('getDocTypes', [], 'operation.getDocTypes returned ex');

        return array_map(
            fn ($t) => DocTypeDto::fromArray((array) $t),
            (array) ($data['types'] ?? [])
        );
    }

    /**
     * Cached doc types.
     *
     * @return DocTypeDto[]
     */
    public function docTypesCached(?int $ttlSeconds = null): array
    {
        $ttl = $ttlSeconds ?? (int) config('fina.cache.doc_types_ttl', 3600);

        return Cache::remember(
            $this->docTypesCacheKey(),
            $ttl,
            fn () => $this->docTypes()
        );
    }

    /**
     * @return DocTypeDto[]
     */
    public function supportedDocTypes(): array
    {
        return array_values(array_filter(
            $this->docTypes(),
            static fn (DocTypeDto $t) => $t->apiSupported === true
        ));
    }

    /**
     * @return DocTypeDto[]
     */
    public function supportedDocTypesCached(?int $ttlSeconds = null): array
    {
        return array_values(array_filter(
            $this->docTypesCached($ttlSeconds),
            static fn (DocTypeDto $t) => $t->apiSupported === true
        ));
    }

    public function findDocType(int $id): ?DocTypeDto
    {
        foreach ($this->docTypes() as $t) {
            if ($t->id === $id) {
                return $t;
            }
        }

        return null;
    }

    public function isDocTypeSupported(int $id): bool
    {
        $t = $this->findDocType($id);

        return $t?->apiSupported === true;
    }

    private function docTypesCacheKey(): string
    {
        $prefix = (string) config('fina.cache.prefix', 'fina-sdk');

        // avoid collisions across envs/accounts
        $baseUrl = (string) config('fina.base_url', '');
        $login = (string) config('fina.login', '');

        return $prefix.':doc-types:'.sha1($baseUrl.'|'.$login);
    }
}
