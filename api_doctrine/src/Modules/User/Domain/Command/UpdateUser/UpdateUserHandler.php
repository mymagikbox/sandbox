<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Command\UpdateUser;

use PhpLab\Domain\Exception\Validator\ValidationException;
use PhpLab\Modules\User\Domain\Enum\UserRole;
use PhpLab\Modules\User\Domain\Enum\UserStatus;
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

        if ($user->getEmail() != $command->email && $this->userRepository->hasByEmail($command->email)) {
            ValidationException::create('email', UserAlreadyExistException::MESSAGE);
        }

        $user->updateData(
            $command->username,
            $command->email,
            UserRole::from($command->role),
            UserStatus::from($command->status),
        );

        $this->userRepository->addToSave($user);
        $this->userRepository->save();
    }
}