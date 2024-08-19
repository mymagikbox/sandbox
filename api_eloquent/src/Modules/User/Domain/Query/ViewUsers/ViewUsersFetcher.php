<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Query\ViewUsers;

use PhpLab\Modules\User\Domain\Exception\UserNotFoundException;
use PhpLab\Modules\User\Domain\Repository\UserRepositoryInterface;

final class ViewUsersFetcher
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    )
    {
    }

    public function fetch(int $userId): ViewUsersRespond
    {
        $user = $this->userRepository->findById($userId);

        if(!$user) {
            throw new UserNotFoundException();
        }

        return new ViewUsersRespond($user);
    }
}