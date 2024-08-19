<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Command\RefreshToken;

use PhpLab\Modules\User\Domain\JwtRefreshTokenPayload;

final class RefreshTokenCommand
{
    public function __construct(
        public readonly JwtRefreshTokenPayload $jwtPayload,
        public readonly string $refreshToken,
    ) {
    }
}