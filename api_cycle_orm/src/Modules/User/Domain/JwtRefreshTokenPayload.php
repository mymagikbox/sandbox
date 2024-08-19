<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain;

final class JwtRefreshTokenPayload extends JwtAccessTokenPayload
{
    const DEFAULT_TOKEN_LIFETIME = '+30 days'; // +10 hour
}