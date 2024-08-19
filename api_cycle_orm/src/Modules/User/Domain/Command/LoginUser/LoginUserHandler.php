<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Command\LoginUser;

use PhpLab\Domain\Helper\DateTimeHelper;
use PhpLab\Modules\User\Domain\Exception\IncorrectAuthNumberException;
use PhpLab\Modules\User\Domain\Exception\UserWithPasswordNotFoundException;
use PhpLab\Modules\User\Domain\Model\RefreshToken;
use PhpLab\Modules\User\Domain\Repository\RefreshTokenRepositoryInterface;
use PhpLab\Modules\User\Domain\Repository\UserRepositoryInterface;
use PhpLab\Modules\User\Domain\Service\AccessTokenService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\Cache\CacheInterface;

final class LoginUserHandler
{
    public const COUNT_LOGIN_ERROR = 5;
    public const BUN_TIME = '+15 minutes';

    public function __construct(
        private readonly UserRepositoryInterface        $userRepository,
        private readonly RefreshTokenRepositoryInterface $refreshTokenRepository,
        private readonly AccessTokenService             $accessTokenService,
        private readonly CacheInterface                 $cache,
        private readonly LoggerInterface                $logger,
    )
    {
    }

    public function run(LoginUserCommand $command): LoginUserRespond
    {
        /** @var CacheItem $countLoginError */
        $countLoginError = $this->cache->getItem('countLoginError');

        if ($countLoginError->isHit() && $countLoginError->get() >= self::COUNT_LOGIN_ERROR) {
            throw new IncorrectAuthNumberException();
        }

        $user = $this->userRepository->findByEmail($command->email);

        if ($user && $user->getPassword()->isValidPassword($command->password)) {

            $this->refreshTokenRepository->deleteExpiredTokens($user->getId());

            $accessToken = $this->accessTokenService->generateAccessToken($user);
            [$refreshToken, $expiresAt] = $this->accessTokenService->generateRefreshToken($user);

            $token = new RefreshToken(
                $user,
                $refreshToken,
                $expiresAt,
                0,
                $command->device_info
            );

            $this->refreshTokenRepository->addToSave($token);
            $this->refreshTokenRepository->save();

            return new LoginUserRespond($accessToken, $refreshToken);
        }

        $bunTime = DateTimeHelper::createDateTime(self::BUN_TIME);
        $countLoginError->expiresAt($bunTime);
        $countLoginError->set($countLoginError->get() + 1);

        if (!$this->cache->save($countLoginError)) {
            $this->logger->error('=== CAN NOT SAVE CACHE ITEM $countLoginError ===');
        }

        throw new UserWithPasswordNotFoundException();
    }
}