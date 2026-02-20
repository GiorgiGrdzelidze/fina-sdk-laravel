<?php

declare(strict_types=1);

/**
 * Operation DTO: response from getBonusCoeff.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

final class BonusCoeffResponse
{
    public function __construct(
        public readonly float $coeff,
        public readonly mixed $ex
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            (float) ($data['coeff'] ?? 0.0),
            $data['ex'] ?? null
        );
    }
}
