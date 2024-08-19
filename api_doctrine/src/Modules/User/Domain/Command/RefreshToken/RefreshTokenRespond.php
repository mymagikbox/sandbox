<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Command\RefreshToken;

use JsonSerializable;

final class RefreshTokenRespond implements JsonSerializable
{
    public function __construct(
        private readonly string $accessToken = '',
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'access_token' => $this->accessToken,
        ];
    }
}