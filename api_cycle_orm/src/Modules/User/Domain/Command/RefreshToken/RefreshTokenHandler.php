<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Command\RefreshToken;

use PhpLab\Modules\User\Domain\Exception\UserNotFoundException;
use PhpLab\Modules\User\Domain\Repository\RefreshTokenRepositoryInterface;
use PhpLab\Modules\User\Domain\Repository\UserRepositoryInterface;
use PhpLab\Modules\User\Domain\Service\AccessTokenService;

final class RefreshTokenHandler
{
    public function __construct(
        private readonly UserRepositoryInterface        $userRepository,
        private readonly RefreshTokenRepositoryInterface $refreshTokenRepository,
        private readonly AccessTokenService             $accessTokenService,
    )
    {
    }

    public function run(RefreshTokenCommand $command): RefreshTokenRespond
    {
        $user = $this->userRepository->findById($command->jwtPayload->getUserId());
        $token = $this->refreshTokenRepository->findByToken($command->refreshToken);

        if ($user && $token) {
            $this->refreshTokenRepository->deleteExpiredTokens($user->getId());

            $accessToken = $this->accessTokenService->generateAccessToken($user);

            $token->incrementRefreshCount();

            $this->refreshTokenRepository->addToSave($token);
            $this->refreshTokenRepository->save();

            return new RefreshTokenRespond($accessToken);
        }

        throw new UserNotFoundException();
    }
}