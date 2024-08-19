<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain;

use DateTimeImmutable;
use JsonSerializable;
use PhpLab\Domain\Helpers\DateTimeHelper;

class JwtTokenPayload implements JsonSerializable
{
    public const DEFAULT_TOKEN_LIFETIME = '+10 hour'; // +10 hour

    private int $userId;
    private string $username;
    private string $role;
    private string $audience; // // OPTIONAL for recipients (frontend url)
    private DateTimeImmutable $issuedAt; // OPTIONAL (current time) time at which the JWT was issued
    private DateTimeImmutable $expiresAt;

    public function __construct(
        int $userId,
        string $username,
        string $role,
    )
    {
        $this->setUserId($userId);
        $this->setUsername($username);
        $this->setRole($role);
    }

    public static function fromTokenData(\stdClass $rawPayload = null): ?static
    {
        $instance = null;

        if($rawPayload && property_exists($rawPayload, 'iss')) {
            $userId = property_exists($rawPayload->iss, 'userId') ? $rawPayload->iss->userId : 0;
            $username = property_exists($rawPayload->iss, 'username') ? $rawPayload->iss->username : '';
            $role = property_exists($rawPayload->iss, 'role') ? $rawPayload->iss->role : '';

            $instance = new static($userId, $username, $role);

            $instance->setAudience(property_exists($rawPayload,'aud') ? $rawPayload->aud : '');
        }

        return $instance;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;
        return $this;
    }

    public function getAudience(): string
    {
        return $this->audience;
    }

    public function setAudience(string $audience): static
    {
        $this->audience = $audience;
        return $this;
    }

    public function getIssuedAt(): DateTimeImmutable
    {
        return $this->issuedAt;
    }

    public function setIssuedAt(DateTimeImmutable $issuedAt): static
    {
        $this->issuedAt = DateTimeHelper::applyTZ($issuedAt);
        return $this;
    }

    public function getExpiresAt(): DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(DateTimeImmutable $expiresAt): static
    {
        $this->expiresAt = DateTimeHelper::applyTZ($expiresAt);
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'iss' => [
                'userId'    => $this->getUserId(),
                'username'  => $this->getUsername(),
                'role'      => $this->getRole(),
            ],
            'aud' => $this->getAudience(),
            'iat' => $this->getIssuedAt()->format('U'),
            'exp' => $this->getExpiresAt()->format('U'),
        ];
    }
}