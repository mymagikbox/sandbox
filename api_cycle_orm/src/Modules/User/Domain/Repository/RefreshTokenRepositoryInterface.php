<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Repository;

use PhpLab\Modules\User\Domain\Model\RefreshToken;
use PhpLab\Domain\Interface\Repository\RepositorySaveInterface;

interface RefreshTokenRepositoryInterface extends RepositorySaveInterface
{
    public function deleteExpiredTokens(int $userId): void;
    public function deleteUserTokens(int $userId): void;
    public function deleteToken(string $refreshToken): void;
    public function findByToken(string $refreshToken): ?RefreshToken;
}
