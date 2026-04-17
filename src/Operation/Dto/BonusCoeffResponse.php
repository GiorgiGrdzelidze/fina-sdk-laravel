<?php

declare(strict_types=1);

/**
 * Operation DTO: response from getBonusCoeff.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

final readonly class BonusCoeffResponse
{
    /**
     * @param  float  $coeff  The bonus accumulation coefficient.
     * @param  mixed  $ex  Error field (null on success).
     */
    public function __construct(
        public float $coeff,
        public mixed $ex
    ) {}

    /**
     * @param  array<string, mixed>  $data  Raw API response array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (float) ($data['coeff'] ?? 0.0),
            $data['ex'] ?? null
        );
    }
}
