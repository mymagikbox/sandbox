<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Command\LoginUser;

use JsonSerializable;

final class LoginUserRespond implements JsonSerializable
{
    public function __construct(
        private readonly string $accessToken = '',
        private readonly string $refreshToken = '',
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refreshToken,
        ];
    }
}