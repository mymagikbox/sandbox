<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Command\CreateUser;

use PhpLab\Domain\Exception\Validator\ValidationException;
use PhpLab\Domain\Interface\Event\EventDispatcherInterface;
use PhpLab\Modules\User\Domain\Enum\UserRole;
use PhpLab\Modules\User\Domain\Enum\UserStatus;
use PhpLab\Modules\User\Domain\Event\UserCreatedEvent;
use PhpLab\Modules\User\Domain\Exception\UserAlreadyExistException;
use PhpLab\Modules\User\Domain\Model\Entity\Password;
use PhpLab\Modules\User\Domain\Model\User;
use PhpLab\Modules\User\Domain\Repository\UserRepositoryInterface;

final class CreateUserHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly EventDispatcherInterface $eventDispatcher,
    )
    {
    }

    public function run(CreateUserCommand $command): void
    {
        if ($this->userRepository->hasByEmail($command->email)) {
            ValidationException::create('email', UserAlreadyExistException::MESSAGE);
        }

        $user = new User(
            $command->username,
            $command->email,
            Password::create($command->password),
            UserRole::from($command->role),
            UserStatus::from($command->status)
        );

        $this->userRepository->addToSave($user);
        $this->userRepository->save();

        $this->eventDispatcher->dispatch(new UserCreatedEvent($user), UserCreatedEvent::NAME);
    }
}