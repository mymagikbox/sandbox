<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Command\LogoutUser;


use PhpLab\Modules\User\Domain\Repository\RefreshTokenRepositoryInterface;
use Psr\Log\LoggerInterface;

final class LogoutUserHandler
{
    public function __construct(
        private readonly RefreshTokenRepositoryInterface $refreshTokenRepository,
        private readonly LoggerInterface                $logger,
    )
    {
    }

    public function run(LogoutUserCommand $command): void
    {
        if ($command->jwtPayload) {
            $this->refreshTokenRepository->deleteExpiredTokens($command->jwtPayload->getUserId());
            $this->refreshTokenRepository->deleteToken($command->refreshToken);

            $this->logger->debug("Logout user by refresh token", [$command->refreshToken]);
        }
    }
}