<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Query\ListUsers;

use JsonSerializable;
use PhpLab\Domain\Query\Paginator;
use PhpLab\Domain\Query\Sorter;

final class ListUsersRespond implements JsonSerializable
{
    public function __construct(
        public readonly Paginator $page,
        public readonly Sorter $sorter,
        public readonly ListUsersCollection $collection,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'pagination'    => $this->page,
            'sorter'        => $this->sorter,
            'results'       => $this->collection,
        ];
    }
}