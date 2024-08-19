<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain;

use DateTimeImmutable;
use JsonSerializable;
use PhpLab\Domain\Helpers\DateTimeHelper;
use PhpLab\Modules\User\Domain\Enum\UserRole;

class JwtTokenPayload implements JsonSerializable
{
    public const DEFAULT_TOKEN_LIFETIME = '+10 hour'; // +10 hour

    public function __construct(
        private readonly int    $userId,
        private readonly string $username,
        private readonly string $email,
        private readonly UserRole $role,
        private readonly DateTimeImmutable $issuedAt, // OPTIONAL (current time) time at which the JWT was issued
        private readonly DateTimeImmutable $expiresAt, // expired time
        private readonly ?string $audience = null // OPTIONAL for recipients (frontend url)
    )
    {
    }

    public static function fromTokenData(\stdClass $rawPayload = null): ?static
    {
        $instance = null;

        if($rawPayload && property_exists($rawPayload, 'iss')) {
            $ttl = static::DEFAULT_TOKEN_LIFETIME;

            $userId = property_exists($rawPayload->iss, 'userId') ? $rawPayload->iss->userId : 0;
            $username = property_exists($rawPayload->iss, 'username') ? $rawPayload->iss->username : '';
            $email = property_exists($rawPayload->iss, 'email') ? $rawPayload->iss->email : '';
            $role = property_exists($rawPayload->iss, 'role') ? $rawPayload->iss->role : '';
            $audience = property_exists($rawPayload,'aud') ? $rawPayload->aud : $_ENV['ADMIN_URL'];
            $issuedAt = property_exists($rawPayload,'iat') ? DateTimeHelper::createDateTime($rawPayload->iat) : DateTimeHelper::createDateTime();
            $expiresAt = property_exists($rawPayload,'exp') ? DateTimeHelper::createDateTime($rawPayload->exp) : DateTimeHelper::createDateTime($ttl);

            $instance = new static(
                $userId,
                $username,
                $email,
                UserRole::from($role),
                $issuedAt,
                $expiresAt,
                $audience
            );
        }

        return $instance;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function jsonSerialize(): array
    {
        return [
            'iss' => [
                'userId'    => $this->userId,
                'username'  => $this->username,
                'email'     => $this->email,
                'role'      => $this->role->value,
            ],
            'aud' => $this->audience,
            'iat' => $this->issuedAt->format('U'),
            'exp' => $this->expiresAt->format('U'),
        ];
    }
}