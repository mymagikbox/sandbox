<?php
declare(strict_types=1);

namespace PhpLab\Infrastructure\Query;

use JsonSerializable;

class Paginator implements JsonSerializable
{
    const DEFAULT_LIMIT = 10;

    private int $total = 0;

    public function __construct(
        public readonly int $page = 1,
        public readonly int $limit = self::DEFAULT_LIMIT
    )
    {
    }

    public static function create(array $data): static
    {
        return new static(
            (isset($data['page']) && $data['page']) ? (int) $data['page'] : 1,
            (isset($data['limit']) && $data['limit']) ? (int) $data['limit'] :  self::DEFAULT_LIMIT
        );
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    public function jsonSerialize(): array
    {
        return [
            'total'     => $this->getTotal(),
            'page'      => $this->page,
            'limit'     => $this->limit,
        ];
    }
}