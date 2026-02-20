<?php

declare(strict_types=1);

/**
 * Operation DTO: staff member reference data.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

final class StaffDto
{
    /**
     * @param  StaffAdditionalFieldDto[]  $addFields
     */
    public function __construct(
        public readonly int $id,
        public readonly int $groupId,
        public readonly string $name,
        public readonly string $privateNum,
        public readonly string $passportNum,
        public readonly string $address,
        public readonly string $tel,
        public readonly string $comment,
        public readonly array $addFields,
    ) {}

    public static function fromArray(array $data): self
    {
        $fields = array_map(
            fn ($f) => StaffAdditionalFieldDto::fromArray((array) $f),
            (array) ($data['add_fields'] ?? [])
        );

        return new self(
            (int) ($data['id'] ?? 0),
            (int) ($data['group_id'] ?? 0),
            (string) ($data['name'] ?? ''),
            (string) ($data['private_num'] ?? ''),
            (string) ($data['passport_num'] ?? ''),
            (string) ($data['address'] ?? ''),
            (string) ($data['tel'] ?? ''),
            (string) ($data['comment'] ?? ''),
            $fields,
        );
    }
}
