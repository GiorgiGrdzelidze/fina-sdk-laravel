<?php

declare(strict_types=1);

/**
 * Operation DTO: staff member reference data.
 */

namespace Fina\Sdk\Laravel\Operation\Dto;

final readonly class StaffDto
{
    /**
     * @param  StaffAdditionalFieldDto[]  $addFields
     */
    public function __construct(
        public int $id,
        public int $groupId,
        public string $name,
        public string $privateNum,
        public string $passportNum,
        public string $address,
        public string $tel,
        public string $comment,
        public array $addFields,
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
