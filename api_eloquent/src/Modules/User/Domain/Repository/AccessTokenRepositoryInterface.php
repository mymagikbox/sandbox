<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Repository;

use PhpLab\Modules\User\Domain\AccessToken;

interface AccessTokenRepositoryInterface
{
    public function create(AccessToken $token): void;
    public function deleteOldTokens(int $userId): void;
    public function deleteUserTokens(int $userId): void;
    public function deleteByToken(string $refreshToken): void;
    public function isTokenExist(string $refreshToken): bool;
    public function incrementRefreshCount(string $refreshToken): void;
}
