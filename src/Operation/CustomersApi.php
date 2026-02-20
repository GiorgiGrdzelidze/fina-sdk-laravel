<?php

declare(strict_types=1);

/**
 * Operation API client for customer-related endpoints.
 */

namespace Fina\Sdk\Laravel\Operation;

use Fina\Sdk\Laravel\Endpoints\BaseApi;

/**
 * Provides methods for retrieving customer data from the FINA Operation API.
 */
final class CustomersApi extends BaseApi
{
    public function __construct(\Fina\Sdk\Laravel\Client\FinaClient $client)
    {
        parent::__construct($client, 'operation');
    }

    /**
     * getCustomersByCode - Customers by identification code
     * GET api/operation/getCustomersByCode/{code}
     */
    public function getByCode(string $code): array
    {
        return $this->get('getCustomersByCode/'.rawurlencode($code), [], 'operation.getCustomersByCode returned ex');
    }

    /**
     * getCustomers - Customers list
     * GET api/operation/getCustomers
     */
    public function all(): array
    {
        return $this->get('getCustomers', [], 'operation.getCustomers returned ex');
    }

    /**
     * getCustomerGroups - Customer groups
     * GET api/operation/getCustomerGroups
     */
    public function groups(): array
    {
        return $this->get('getCustomerGroups', [], 'operation.getCustomerGroups returned ex');
    }

    /**
     * getCustomerAddresses - Customer addresses
     * GET api/operation/getCustomerAddresses/{customer_id}
     */
    public function addresses(int $customerId): array
    {
        return $this->get('getCustomerAddresses/'.$customerId, [], 'operation.getCustomerAddresses returned ex');
    }

    /**
     * getCustomerAdditionalFields - Customer additional fields metadata
     * GET api/operation/getCustomerAdditionalFields
     */
    public function additionalFields(): array
    {
        return $this->get('getCustomerAdditionalFields', [], 'operation.getCustomerAdditionalFields returned ex');
    }
}
