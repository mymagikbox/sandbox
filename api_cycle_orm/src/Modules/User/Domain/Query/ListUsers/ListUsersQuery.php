<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Query\ListUsers;

use PhpLab\Domain\Query\Paginator;
use PhpLab\Domain\Query\Sorter;

final class ListUsersQuery
{
    public function __construct(
        public readonly Paginator $page,
        public readonly Sorter $sorter,
        public readonly ListUsersFilter $filter,
    ) {
    }

    public static function create(array $data): self
    {
        return new self(
            Paginator::create($data),
            Sorter::create($data),
            ListUsersFilter::create($data['f'] ?? [])
        );
    }
}