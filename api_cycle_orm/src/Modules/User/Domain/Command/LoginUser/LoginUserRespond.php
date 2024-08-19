<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Command\LoginUser;

use JsonSerializable;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "AdminAuthLoginRespond",
    required: ['access_token', 'refresh_token',],
    properties: [
        new OA\Property(property:'access_token', type: 'string'),
        new OA\Property(property:'refresh_token', type: 'string'),
    ],
    type: 'object'
)]
final readonly class LoginUserRespond implements JsonSerializable
{
    public function __construct(
        private string $accessToken = '',
        private string $refreshToken = '',
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'access_token'  => $this->accessToken,
            'refresh_token' => $this->refreshToken,
        ];
    }
}