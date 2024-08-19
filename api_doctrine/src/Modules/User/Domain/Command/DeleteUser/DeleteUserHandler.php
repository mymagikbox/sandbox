<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Command\DeleteUser;

use PhpLab\Modules\User\Domain\Exception\CantDeleteItselfException;
use PhpLab\Modules\User\Domain\Exception\UserNotFoundException;
use PhpLab\Modules\User\Domain\Repository\AccessTokenRepositoryInterface;
use PhpLab\Modules\User\Domain\Repository\UserRepositoryInterface;
use Psr\Log\LoggerInterface;

final class DeleteUserHandler
{
    public function __construct(
        private readonly UserRepositoryInterface        $userRepository,
        private readonly AccessTokenRepositoryInterface $accessTokenRepository,
        private readonly LoggerInterface                $logger,
    )
    {
    }

    public function run(DeleteUserCommand $command): void
    {
        if ($command->jwtPayload) {

            if ($command->userId === $command->jwtPayload->getUserId()) {
                throw new CantDeleteItselfException();
            }

            $user = $this->userRepository->findById($command->userId);

            if(!$user) {
                throw new UserNotFoundException();
            }

            $user->markAsDeleted();

            $this->userRepository->addToSave($user);
            $this->userRepository->save();

            $this->accessTokenRepository->deleteUserTokens($command->userId);

            $this->logger->debug("Delete user with ID: ", [$command->userId]);
        }
    }
}