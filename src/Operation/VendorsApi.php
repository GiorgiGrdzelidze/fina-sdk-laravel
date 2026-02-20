<?php

declare(strict_types=1);

/**
 * Operation API client for vendor-related endpoints.
 */

namespace Fina\Sdk\Laravel\Operation;

use Fina\Sdk\Laravel\Endpoints\BaseApi;

/**
 * Provides methods for retrieving vendor data from the FINA Operation API.
 */
final class VendorsApi extends BaseApi
{
    public function __construct(\Fina\Sdk\Laravel\Client\FinaClient $client)
    {
        parent::__construct($client, 'operation');
    }

    public function getByCode(string $code): array
    {
        return $this->get('getVendorsByCode/'.rawurlencode($code), [], 'operation.getVendorsByCode returned ex');
    }

    public function all(): array
    {
        return $this->get('getVendors', [], 'operation.getVendors returned ex');
    }

    public function groups(): array
    {
        return $this->get('getVendorGroups', [], 'operation.getVendorGroups returned ex');
    }

    public function addresses(int $vendorId): array
    {
        return $this->get('getVendorAddresses/'.$vendorId, [], 'operation.getVendorAddresses returned ex');
    }

    public function additionalFields(): array
    {
        return $this->get('getVendorAdditionalFields', [], 'operation.getVendorAdditionalFields returned ex');
    }
}
