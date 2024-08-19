<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Repository;

use PhpLab\Modules\User\Domain\Model\RefreshToken;
use PhpLab\Modules\User\Domain\Model\User;

interface RefreshTokenRepositoryInterface
{
    public function addToSave(RefreshToken $token): void;
    public function save(): void;
    public function deleteExpiredTokens(int $userId): void;
    public function deleteUserTokens(int $userId): void;
    public function deleteByToken(string $refreshToken): void;
    public function isTokenExist(string $refreshToken): bool;
    public function incrementRefreshCount(string $refreshToken): void;
}
