<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Model;

use DateTimeImmutable;
use PhpLab\Domain\Service\UlidService;

final class RefreshToken
{
    private string $id;

    public function __construct(
        private User $user,
        private string $refreshToken,
        private DateTimeImmutable $expiredAt,
        private int $refreshCount,
        private ?string $deviceInfo = null,
    )
    {
        $this->id = UlidService::generate();
    }
}