<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Services;

use Exception;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PhpLab\Modules\User\Domain\Exception\InvalidTokenException;
use PhpLab\Modules\User\Domain\JwtAccessTokenPayload;
use PhpLab\Modules\User\Domain\JwtRefreshTokenPayload;
use PhpLab\Modules\User\Domain\User;
use PhpLab\Domain\Helpers\DateTimeHelper;
use Psr\Log\LoggerInterface;

class AccessTokenService
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly string $accessSecretKey,
        private readonly string $refreshSecretKey,
        private readonly string $algorithm
    )
    {
        if (!$accessSecretKey || !$refreshSecretKey) {
            throw new Exception('Access or refresh secret key is empty.', E_USER_ERROR);
        }
    }

    public function parseAccessToken(string $tokenString): ?JwtAccessTokenPayload
    {
        try {
            $tokenData = JWT::decode(
                $tokenString,
                new Key($this->accessSecretKey, $this->algorithm),
            );

            return JwtAccessTokenPayload::fromTokenData($tokenData);
        } catch (SignatureInvalidException $e) {
            $this->logger->warning('--------');
            $this->logger->warning('=== TRY HACK ACCESS TOKEN ===');
            $this->logger->warning(var_export($_REQUEST, true));
            $this->logger->warning(var_export($_SERVER, true));
            $this->logger->warning('--------');

            throw new InvalidTokenException();
        } catch (Exception $e) {
            throw new InvalidTokenException();
        }
    }

    public function parseRefreshToken(string $tokenString): ?JwtRefreshTokenPayload
    {
        try {
            $tokenData = JWT::decode(
                $tokenString,
                new Key($this->refreshSecretKey, $this->algorithm),
            );

            return JwtRefreshTokenPayload::fromTokenData($tokenData);
        } catch (SignatureInvalidException $e) {
            $this->logger->warning('--------');
            $this->logger->warning('=== TRY HACK REFRESH TOKEN ===');
            $this->logger->warning(var_export($_REQUEST, true));
            $this->logger->warning(var_export($_SERVER, true));
            $this->logger->warning('--------');

            throw new InvalidTokenException();
        } catch (Exception $e) {
            throw new InvalidTokenException();
        }
    }

    public function generateAccessToken(User $user): string
    {
        $ttl = JwtAccessTokenPayload::DEFAULT_TOKEN_LIFETIME;
        $issuedAt = DateTimeHelper::createDateTime();
        $expiresAt = DateTimeHelper::createDateTime($ttl);

        $jwtPayload = new JwtAccessTokenPayload(
            $user->getId(),
            $user->getUsername(),
            $user->getRole()->getValue()
        );

        $jwtPayload
            ->setAudience($_ENV['ADMIN_URL'])
            ->setIssuedAt($issuedAt)
            ->setExpiresAt($expiresAt);

        return JWT::encode($jwtPayload->jsonSerialize(), $this->accessSecretKey, $this->algorithm);
    }

    public function generateRefreshToken(User $user): array
    {
        $ttl = JwtRefreshTokenPayload::DEFAULT_TOKEN_LIFETIME;
        $issuedAt = DateTimeHelper::createDateTime();
        $expiresAt = DateTimeHelper::createDateTime($ttl);

        $jwtPayload = new JwtRefreshTokenPayload(
            $user->getId(),
            $user->getUsername(),
            $user->getRole()->getValue()
        );

        $jwtPayload
            ->setAudience($_ENV['ADMIN_URL'])
            ->setIssuedAt($issuedAt)
            ->setExpiresAt($expiresAt);

        return [
            JWT::encode($jwtPayload->jsonSerialize(), $this->refreshSecretKey, $this->algorithm),
            $expiresAt
        ];
    }
}