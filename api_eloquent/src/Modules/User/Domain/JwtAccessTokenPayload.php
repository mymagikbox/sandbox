<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain;

class JwtAccessTokenPayload extends JwtTokenPayload
{
    const DEFAULT_TOKEN_LIFETIME = '+1 minutes'; // +10 hour
}