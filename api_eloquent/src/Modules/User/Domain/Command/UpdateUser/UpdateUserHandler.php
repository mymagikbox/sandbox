<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Command\UpdateUser;

use PhpLab\Application\Validator\ValidationException;
use PhpLab\Modules\User\Domain\Entity\Role;
use PhpLab\Modules\User\Domain\Entity\Status;
use PhpLab\Modules\User\Domain\Exception\UserAlreadyExistException;
use PhpLab\Modules\User\Domain\Exception\UserNotFoundException;
use PhpLab\Modules\User\Domain\Repository\UserRepositoryInterface;

final class UpdateUserHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    )
    {
    }

    public function run(int $userId, UpdateUserCommand $command): void
    {
        $user = $this->userRepository->findById($userId);

        if(!$user) {
            throw new UserNotFoundException();
        }

        if ($user->getUsername() != $command->username && $this->userRepository->hasByUserName($command->username)) {
            ValidationException::create('username', UserAlreadyExistException::MESSAGE);
        }

        $user
            ->setUsername($command->username)
            ->setRole(Role::create($command->role))
            ->setStatus(Status::create($command->status));

        $this->userRepository->update($user);
    }
}