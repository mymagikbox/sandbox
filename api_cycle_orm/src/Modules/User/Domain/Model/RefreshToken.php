<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Model;

use DateTimeImmutable;
use PhpLab\Domain\Interface\Model\DomainModelInterface;

class RefreshToken implements DomainModelInterface
{
    const TABLE = 'refresh_token';

    private int $userId;

    public function __construct(
        private User $user,
        private string $refreshToken,
        private DateTimeImmutable $expiredAt,
        private int $refreshCount,
        private ?string $deviceInfo = null,
    )
    {
        $this->userId = $user->getId();
    }

    public function incrementRefreshCount(): void
    {
        $this->refreshCount++;
    }
}