<?php

declare(strict_types=1);

/**
 * Operation API client for saving and retrieving FINA documents.
 */

namespace Fina\Sdk\Laravel\Operation;

use Fina\Sdk\Laravel\Contracts\ArrayPayload;
use Fina\Sdk\Laravel\Contracts\ValidatesPayload;
use Fina\Sdk\Laravel\Endpoints\BaseApi;
use Fina\Sdk\Laravel\Operation\Dto\Production\ProductionDocDto;
use Fina\Sdk\Laravel\Operation\Dto\SaveDocResponse;
use Fina\Sdk\Laravel\Support\PayloadValidator;

/**
 * Provides generic and typed methods for saving and retrieving documents
 * (orders, returns, money operations, productions, etc.) via the FINA Operation API.
 *
 * Payloads implementing {@see ValidatesPayload} are validated automatically before submission.
 */
final class DocumentsApi extends BaseApi
{
    public function __construct(\Fina\Sdk\Laravel\Client\FinaClient $client)
    {
        parent::__construct($client, 'operation');
    }

    /**
     * Generic saver for any saveDoc* endpoint.
     * - If payload implements ValidatesPayload -> validates automatically (fail-fast)
     */
    public function save(string $methodName, ArrayPayload|array $payload): SaveDocResponse
    {
        if ($payload instanceof ValidatesPayload) {
            PayloadValidator::validate($payload);
        }

        $body = $payload instanceof ArrayPayload ? $payload->toArray() : $payload;

        $data = $this->post($methodName, $body, "operation.{$methodName} returned ex");

        return SaveDocResponse::fromArray($data);
    }

    /**
     * Generic getter for any getDoc* endpoint.
     */
    public function getDoc(string $methodName, int $id): array
    {
        return $this->get($methodName.'/'.$id, [], "operation.{$methodName} returned ex");
    }

    // -----------------------
    // saveDoc* wrappers
    // -----------------------

    public function saveCustomerOrder(ArrayPayload|array $payload): SaveDocResponse
    {
        return $this->save('saveDocCustomerOrder', $payload);
    }

    public function saveProductOut(ArrayPayload|array $payload): SaveDocResponse
    {
        return $this->save('saveDocProductOut', $payload);
    }

    public function saveProductMove(ArrayPayload|array $payload): SaveDocResponse
    {
        return $this->save('saveDocProductMove', $payload);
    }

    public function saveProvidedService(ArrayPayload|array $payload): SaveDocResponse
    {
        return $this->save('saveDocProvidedService', $payload);
    }

    public function saveCustomerReturn(ArrayPayload|array $payload): SaveDocResponse
    {
        return $this->save('saveDocCustomerReturn', $payload);
    }

    public function saveProductIn(ArrayPayload|array $payload): SaveDocResponse
    {
        return $this->save('saveDocProductIn', $payload);
    }

    public function saveProductCancel(ArrayPayload|array $payload): SaveDocResponse
    {
        return $this->save('saveDocProductCancel', $payload);
    }

    public function saveCafeOrder(ArrayPayload|array $payload): SaveDocResponse
    {
        return $this->save('saveDocCafeOrder', $payload);
    }

    public function saveCustomerMoneyIn(ArrayPayload|array $payload): SaveDocResponse
    {
        return $this->save('saveDocCustomerMoneyIn', $payload);
    }

    public function saveCustomerAdvanceIn(ArrayPayload|array $payload): SaveDocResponse
    {
        return $this->save('saveDocCustomerAdvanceIn', $payload);
    }

    public function saveCustomerMoneyReturn(ArrayPayload|array $payload): SaveDocResponse
    {
        return $this->save('saveDocCustomerMoneyReturn', $payload);
    }

    public function saveBonusPayment(ArrayPayload|array $payload): SaveDocResponse
    {
        return $this->save('saveDocBonusPayment', $payload);
    }

    // ---- Money docs ----

    public function saveCustomerMoneyOut(ArrayPayload|array $payload): SaveDocResponse
    {
        return $this->save('saveDocCustomerMoneyOut', $payload);
    }

    public function saveVendorMoneyIn(ArrayPayload|array $payload): SaveDocResponse
    {
        return $this->save('saveDocVendorMoneyIn', $payload);
    }

    public function saveVendorMoneyOut(ArrayPayload|array $payload): SaveDocResponse
    {
        return $this->save('saveDocVendorMoneyOut', $payload);
    }

    public function saveVendorMoneyReturn(ArrayPayload|array $payload): SaveDocResponse
    {
        return $this->save('saveDocVendorMoneyReturn', $payload);
    }

    // ---- Production docs ----

    public function saveProduction(ArrayPayload|array $payload): SaveDocResponse
    {
        return $this->save('saveDocProduction', $payload);
    }

    public function getProductionTyped(int $id): ProductionDocDto
    {
        $data = $this->getDoc('getDocProduction', $id);

        return ProductionDocDto::fromArray((array) ($data['production'] ?? []));
    }

    // -----------------------
    // getDoc* wrappers (raw)
    // -----------------------

    public function getCustomerOrder(int $id): array
    {
        return $this->getDoc('getDocCustomerOrder', $id);
    }

    public function getProductOut(int $id): array
    {
        return $this->getDoc('getDocProductOut', $id);
    }

    public function getProductMove(int $id): array
    {
        return $this->getDoc('getDocProductMove', $id);
    }

    public function getReceivedService(int $id): array
    {
        return $this->getDoc('getDocReceivedService', $id);
    }

    public function getCustomerReturn(int $id): array
    {
        return $this->getDoc('getDocCustomerReturn', $id);
    }

    public function getProduction(int $id): array
    {
        return $this->getDoc('getDocProduction', $id);
    }
}
