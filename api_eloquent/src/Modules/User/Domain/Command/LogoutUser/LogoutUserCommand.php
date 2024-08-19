<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Command\LogoutUser;

use PhpLab\Modules\User\Domain\JwtRefreshTokenPayload;

final class LogoutUserCommand
{
    public function __construct(
        public readonly JwtRefreshTokenPayload $jwtPayload,
        public readonly string $refreshToken,
    ) {
    }
}