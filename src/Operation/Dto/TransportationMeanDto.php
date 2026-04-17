<?php

declare(strict_types=1);

/**
 * Operation DTO: transportation mean from getTransportationMeans (v8.0).
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

use Fina\Sdk\Laravel\Operation\ReferenceApi;

/**
 * Represents a vehicle/transportation mean registered in FINA.
 *
 * @see ReferenceApi::transportationMeans()
 */
final readonly class TransportationMeanDto
{
    public function __construct(
        public int $id,
        public string $model,
        public string $num,
        public string $driverName,
        public string $driverNum,
        public float $fuelConsumption,
        public int $consumptionType,
        public int $staffId,
        public string $trailer,
    ) {}

    /**
     * @param  array<string, mixed>  $data  Raw API response array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['id'] ?? 0),
            (string) ($data['model'] ?? ''),
            (string) ($data['num'] ?? ''),
            (string) ($data['driver_name'] ?? ''),
            (string) ($data['driver_num'] ?? ''),
            (float) ($data['fuel_consumption'] ?? 0),
            (int) ($data['consumption_type'] ?? 0),
            (int) ($data['staff_id'] ?? 0),
            (string) ($data['trailer'] ?? ''),
        );
    }
}
