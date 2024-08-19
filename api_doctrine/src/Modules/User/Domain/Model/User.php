<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Model;

use DateTimeImmutable;
use PhpLab\Modules\User\Domain\Enum\UserRole;
use PhpLab\Modules\User\Domain\Enum\UserStatus;
use PhpLab\Modules\User\Domain\Model\Entity\Password;

final class User
{
    private ?int $id = null;
    private string $username;
    private string $email;
    private UserStatus $status;
    private UserRole $role;
    private Password $password;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt = null;
    private ?DateTimeImmutable $deletedAt = null;

    public function __construct(
        string $username,
        string $email,
        Password $password,
        UserRole $role,
        UserStatus $status
    )
    {
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
        $this->status = $status;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function updateData(
        string $username,
        string $email,
        UserRole $role,
        UserStatus $status
    ): void
    {
        $this->username = $username;
        $this->email = $email;
        $this->role = $role;
        $this->status = $status;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function markAsDeleted(): void
    {
        $this->status = UserStatus::DELETED;
        $this->deletedAt = new DateTimeImmutable();
    }

    public function getDataForViewUserRespond(): array
    {
        return [
            $this->id,
            $this->username,
            $this->email,
            $this->role,
            $this->status,
        ];
    }

    public function getDataForJwtTokenPayload(): array
    {
        return [
            $this->id,
            $this->username,
            $this->email,
            $this->role,
        ];
    }
}
