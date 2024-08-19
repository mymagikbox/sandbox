<?php
declare(strict_types=1);

namespace PhpLab\Infrastructure\Query;

use JsonSerializable;

class Sorter implements JsonSerializable
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
    ): static
    {
        if(isset($data['sortOrder']) && $data['sortOrder'] && $data['sortOrder'] === self::SORT_ASC) {
            $sortDirection = self::SORT_ASC;
        }

        return new static(
            (isset($data['sortBy']) && $data['sortBy']) ? $data['sortBy'] : 'id',
            $sortDirection
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'sort_by'       => $this->fieldName,
            'sort_order'    => $this->sortDirection,
        ];
    }
}