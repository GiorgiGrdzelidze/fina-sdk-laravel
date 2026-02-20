<?php

declare(strict_types=1);

/**
 * Operation API client for product-related endpoints.
 */

namespace Fina\Sdk\Laravel\Operation;

use DateTimeInterface;
use Fina\Sdk\Laravel\Endpoints\BaseApi;
use Fina\Sdk\Laravel\Support\FinaDate;

/**
 * Provides methods for retrieving product data, prices, units, characteristics,
 * images, barcodes, and stock from the FINA Operation API.
 */
final class ProductsApi extends BaseApi
{
    public function __construct(\Fina\Sdk\Laravel\Client\FinaClient $client)
    {
        parent::__construct($client, 'operation');
    }

    /**
     * GET api/operation/getProductGroups
     */
    public function groups(): array
    {
        return $this->get('getProductGroups', [], 'operation.getProductGroups returned ex');
    }

    /**
     * GET api/operation/getWebProductGroups
     */
    public function webGroups(): array
    {
        return $this->get('getWebProductGroups', [], 'operation.getWebProductGroups returned ex');
    }

    /**
     * GET api/operation/getProducts
     */
    public function all(): array
    {
        return $this->get('getProducts', [], 'operation.getProducts returned ex');
    }

    /**
     * POST api/operation/getProductsArray
     * Request body: int[] (product ids)
     */
    public function byIds(array $productIds): array
    {
        // API expects a raw int[] array in JSON
        return $this->post('getProductsArray', array_values($productIds), 'operation.getProductsArray returned ex');
    }

    /**
     * GET api/operation/getProductsAfter/{after_date}
     */
    public function after(DateTimeInterface $afterDate): array
    {
        $after = rawurlencode(FinaDate::toFina($afterDate));

        return $this->get("getProductsAfter/{$after}", [], 'operation.getProductsAfter returned ex');
    }

    /**
     * POST api/operation/getProductsImageArray
     * Request body: int[] (recommended max ~20)
     */
    public function imagesByProductIds(array $productIds): array
    {
        return $this->post('getProductsImageArray', array_values($productIds), 'operation.getProductsImageArray returned ex');
    }

    /**
     * POST api/operation/getProductsBarcodeArray
     * Request body: int[] (product ids)
     */
    public function barcodesByProductIds(array $productIds): array
    {
        return $this->post('getProductsBarcodeArray', array_values($productIds), 'operation.getProductsBarcodeArray returned ex');
    }

    /**
     * GET api/operation/getProductPrices
     */
    public function prices(): array
    {
        return $this->get('getProductPrices', [], 'operation.getProductPrices returned ex');
    }

    /**
     * GET api/operation/getProductPricesAfter/{after_date}
     */
    public function pricesAfter(DateTimeInterface $afterDate): array
    {
        $after = rawurlencode(FinaDate::toFina($afterDate));

        return $this->get("getProductPricesAfter/{$after}", [], 'operation.getProductPricesAfter returned ex');
    }

    /**
     * GET api/operation/getProductUnits
     */
    public function units(): array
    {
        return $this->get('getProductUnits', [], 'operation.getProductUnits returned ex');
    }

    /**
     * GET api/operation/getCharacteristics
     */
    public function characteristics(): array
    {
        return $this->get('getCharacteristics', [], 'operation.getCharacteristics returned ex');
    }

    /**
     * POST api/operation/getProductsRestArray
     * Request body: { "prods": int[] }
     */
    public function restByProductIds(array $productIds): array
    {
        return $this->post(
            'getProductsRestArray',
            ['prods' => array_values($productIds)],
            'operation.getProductsRestArray returned ex'
        );
    }
}
