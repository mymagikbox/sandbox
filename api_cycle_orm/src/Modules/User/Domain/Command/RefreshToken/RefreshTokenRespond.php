<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Command\RefreshToken;

use JsonSerializable;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "AdminAuthRefreshTokenRespond",
    required: ['access_token',],
    properties: [
        new OA\Property(property:'access_token', type: 'string'),
    ],
    type: 'object'
)]

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