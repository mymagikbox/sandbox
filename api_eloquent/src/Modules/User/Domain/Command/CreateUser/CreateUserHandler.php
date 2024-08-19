<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Command\CreateUser;

use PhpLab\Application\Validator\ValidationException;
use PhpLab\Modules\User\Domain\Entity\Password;
use PhpLab\Modules\User\Domain\Entity\Role;
use PhpLab\Modules\User\Domain\Entity\Status;
use PhpLab\Modules\User\Domain\Exception\UserAlreadyExistException;
use PhpLab\Modules\User\Domain\Repository\UserRepositoryInterface;
use PhpLab\Modules\User\Domain\User;

final class CreateUserHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    )
    {
    }

    public function run(CreateUserCommand $command): void
    {
        if ($this->userRepository->hasByUserName($command->username)) {
            ValidationException::create('username', UserAlreadyExistException::MESSAGE);
        }

        $user = new User(
            $command->username,
            Role::create($command->role),
            Status::create($command->status)
        );
        $user->setPassword(Password::create($command->password));

        $this->userRepository->create($user);
    }
}