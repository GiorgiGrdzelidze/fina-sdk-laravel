<?php

declare(strict_types=1);

/**
 * Operation API client for loyalty and bonus card endpoints.
 */

namespace Fina\Sdk\Laravel\Operation;

use Fina\Sdk\Laravel\Client\FinaClient;
use Fina\Sdk\Laravel\Contracts\ValidatesPayload;
use Fina\Sdk\Laravel\Endpoints\BaseApi;
use Fina\Sdk\Laravel\Operation\Dto\BonusCoeffResponse;
use Fina\Sdk\Laravel\Operation\Dto\BonusOperationPayload;
use Fina\Sdk\Laravel\Operation\Dto\BonusOperationResponse;
use Fina\Sdk\Laravel\Operation\Dto\GiftCardInfoDto;
use Fina\Sdk\Laravel\Support\PayloadValidator;

/**
 * Provides methods for loyalty card lookups, bonus coefficients,
 * and bonus operations via the FINA Operation API.
 */
final class LoyaltyApi extends BaseApi
{
    public function __construct(FinaClient $client)
    {
        parent::__construct($client, 'operation');
    }

    /**
     * GET api/operation/getBonusCoeff
     */
    public function bonusCoeff(): BonusCoeffResponse
    {
        $data = $this->get('getBonusCoeff', [], 'operation.getBonusCoeff returned ex');

        return BonusCoeffResponse::fromArray($data);
    }

    /**
     * GET api/operation/getLoyaltyCardsByHolder/{holder_code}
     * Returns array with 'cards' key (raw)
     */
    public function cardsByHolder(string $holderCode): array
    {
        return $this->get(
            'getLoyaltyCardsByHolder/'.rawurlencode($holderCode),
            [],
            'operation.getLoyaltyCardsByHolder returned ex'
        );
    }

    /**
     * POST api/operation/saveDocBonusOperation
     * Request: {card_id, ref_id, coeff(1|-1), amount}
     * Response: {res, ex}
     */
    public function saveBonusOperation(BonusOperationPayload|array $payload): BonusOperationResponse
    {
        if ($payload instanceof ValidatesPayload) {
            PayloadValidator::validate($payload);
            $body = $payload->toArray();
        } else {
            $body = $payload;
        }

        $data = $this->post('saveDocBonusOperation', $body, 'operation.saveDocBonusOperation returned ex');

        return BonusOperationResponse::fromArray($data);
    }

    /**
     * GET api/operation/getGiftCardInfoByCode/{code}
     * Returns single gift card info by code.
     */
    public function giftCardInfoByCode(string $code): GiftCardInfoDto
    {
        $data = $this->get(
            'getGiftCardInfoByCode/'.rawurlencode($code),
            [],
            'operation.getGiftCardInfoByCode returned ex'
        );

        return GiftCardInfoDto::fromArray((array) ($data['gift'] ?? []));
    }

    /**
     * GET api/operation/getBonusCardRestByCode/{code}
     * Returns bonus card rest by code (raw).
     */
    public function bonusCardRestByCode(string $code): array
    {
        return $this->get(
            'getBonusCardRestByCode/'.rawurlencode($code),
            [],
            'operation.getBonusCardRestByCode returned ex'
        );
    }
}
