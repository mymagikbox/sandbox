<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Query\ListUsers;

use PhpLab\Modules\User\Domain\Repository\UserRepositoryInterface;

final class ListUsersFetcher
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    )
    {
    }

    public function fetch(ListUsersQuery $query): ListUsersRespond
    {
        $userList = $this->userRepository->getListByQuery($query);

        return new ListUsersRespond(
            $query->page,
            $query->sorter,
            ListUsersCollection::create($userList)
        );
    }
}