<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain;

use DateTimeImmutable;
use PhpLab\Modules\User\Domain\Entity\Token;

class AccessToken
{
    private User $user;
    private Token $refreshToken;
    private int $refresh_count;
    private ?string $device_info = null;
    private DateTimeImmutable $created_at;
    private ?DateTimeImmutable $updated_at;

    public function __construct(
        User $user,
        Token $refreshToken,
        int $refreshCount
    )
    {
        $this->setUser($user);
        $this->setRefreshToken($refreshToken);
        $this->setRefreshCount($refreshCount);
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getRefreshToken(): Token
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(Token $refreshToken): static
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    public function getRefreshCount(): int
    {
        return $this->refresh_count;
    }

    public function setRefreshCount(int $refresh_count): static
    {
        $this->refresh_count = $refresh_count;
        return $this;
    }

    public function getDeviceInfo(): ?string
    {
        return $this->device_info;
    }

    public function setDeviceInfo(string $device_info): static
    {
        $this->device_info = $device_info;
        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;
        return $this;
    }
}