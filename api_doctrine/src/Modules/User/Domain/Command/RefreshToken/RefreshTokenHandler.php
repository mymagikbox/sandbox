<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Command\RefreshToken;

use PhpLab\Modules\User\Domain\Exception\UserNotFoundException;
use PhpLab\Modules\User\Domain\Repository\AccessTokenRepositoryInterface;
use PhpLab\Modules\User\Domain\Repository\UserRepositoryInterface;
use PhpLab\Modules\User\Domain\Services\AccessTokenService;

final class RefreshTokenHandler
{
    public function __construct(
        private readonly UserRepositoryInterface        $userRepository,
        private readonly AccessTokenRepositoryInterface $accessTokenRepository,
        private readonly AccessTokenService             $accessTokenService,
    )
    {
    }

    public function run(RefreshTokenCommand $command): RefreshTokenRespond
    {
        $user = $this->userRepository->findById($command->jwtPayload->getUserId());
        $isTokenExist = $this->accessTokenRepository->isTokenExist($command->refreshToken);

        if ($user && $isTokenExist) {
            $this->accessTokenRepository->deleteExpiredTokens($user->getId());

            $accessToken = $this->accessTokenService->generateAccessToken($user);

            $this->accessTokenRepository->incrementRefreshCount($command->refreshToken);

            return new RefreshTokenRespond($accessToken);
        }

        throw new UserNotFoundException();
    }
}