<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Command\RefreshToken;

use PhpLab\Modules\User\Domain\JwtRefreshTokenPayload;

final readonly class RefreshTokenCommand
{
    public function __construct(
        public JwtRefreshTokenPayload $jwtPayload,
        public string                 $refreshToken,
    ) {
    }
}