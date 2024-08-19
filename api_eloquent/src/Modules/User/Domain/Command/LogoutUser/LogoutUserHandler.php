<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Command\LogoutUser;


use PhpLab\Modules\User\Domain\Repository\AccessTokenRepositoryInterface;
use Psr\Log\LoggerInterface;

final class LogoutUserHandler
{
    public function __construct(
        private readonly AccessTokenRepositoryInterface $accessTokenRepository,
        private readonly LoggerInterface                $logger,
    )
    {
    }

    public function run(LogoutUserCommand $command): void
    {
        if ($command->jwtPayload) {
            $this->accessTokenRepository->deleteOldTokens($command->jwtPayload->getUserId());
            $this->accessTokenRepository->deleteByToken($command->refreshToken);

            $this->logger->debug("Logout user by refresh token", [$command->refreshToken]);
        }
    }
}