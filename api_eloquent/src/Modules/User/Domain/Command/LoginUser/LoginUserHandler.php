<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Command\LoginUser;

use PhpLab\Domain\Helpers\DateTimeHelper;
use PhpLab\Modules\User\Domain\AccessToken;
use PhpLab\Modules\User\Domain\Entity\Token;
use PhpLab\Modules\User\Domain\Exception\IncorrectAuthNumberException;
use PhpLab\Modules\User\Domain\Exception\UserWithPasswordNotFoundException;
use PhpLab\Modules\User\Domain\Repository\AccessTokenRepositoryInterface;
use PhpLab\Modules\User\Domain\Repository\UserRepositoryInterface;
use PhpLab\Modules\User\Domain\Services\AccessTokenService;
use PhpLab\Modules\User\Domain\Services\PasswordHasher;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\Cache\CacheInterface;

final class LoginUserHandler
{
    public const COUNT_LOGIN_ERROR = 5;
    public const BUN_TIME = '+15 minutes';

    public function __construct(
        private readonly UserRepositoryInterface        $userRepository,
        private readonly AccessTokenRepositoryInterface $accessTokenRepository,
        private readonly PasswordHasher                 $passwordHasher,
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

        $user = $this->userRepository->findByUsername($command->username);

        if ($user && $this->passwordHasher->validate($command->password, $user->getPassword()->getHash())) {

            $this->accessTokenRepository->deleteOldTokens($user->getId());

            $accessToken = $this->accessTokenService->generateAccessToken($user);
            [$refreshToken, $expiresAt] = $this->accessTokenService->generateRefreshToken($user);

            $token = new AccessToken(
                $user,
                new Token($refreshToken, $expiresAt),
                0
            );

            $this->accessTokenRepository->create($token);

            return new LoginUserRespond($accessToken, $refreshToken);
        }

        $bunTime = DateTimeHelper::createDateTime(static::BUN_TIME);
        $countLoginError->expiresAt($bunTime);
        $countLoginError->set($countLoginError->get() + 1);

        if (!$this->cache->save($countLoginError)) {
            $this->logger->error('=== CAN NOT SAVE CACHE ITEM $countLoginError ===');
        }

        throw new UserWithPasswordNotFoundException();
    }
}