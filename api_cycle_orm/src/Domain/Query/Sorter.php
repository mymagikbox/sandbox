<?php
declare(strict_types=1);

namespace PhpLab\Domain\Query;

use JsonSerializable;
use OpenApi\Attributes as OA;

final class Sorter implements JsonSerializable
{
    const SORT_ASC = 'asc';
    const SORT_DESC = 'desc';

    public function __construct(
        public readonly string $fieldName = 'id',
        public readonly string $sortDirection = self::SORT_DESC,
    )
    {
    }

    public static function create(
        array $data,
        string $sortDirection = self::SORT_DESC
    ): self
    {
        if(isset($data['sortOrder']) && $data['sortOrder'] && $data['sortOrder'] === self::SORT_ASC) {
            $sortDirection = self::SORT_ASC;
        }

        return new self(
            (isset($data['sortBy']) && $data['sortBy']) ? $data['sortBy'] : 'id',
            $sortDirection
        );
    }

    #[OA\Schema(
        schema: "Sorter",
        required: ['sort_by', 'sort_order'],
        properties: [
            new OA\Property(property:'sort_by', type: 'string'),
            new OA\Property(property:'sort_order', type: 'string', enum: [Sorter::SORT_ASC, Sorter::SORT_DESC]),
        ],
        type: 'object'
    )]
    public function jsonSerialize(): array
    {
        return [
            'sort_by'       => $this->fieldName,
            'sort_order'    => $this->sortDirection,
        ];
    }
}