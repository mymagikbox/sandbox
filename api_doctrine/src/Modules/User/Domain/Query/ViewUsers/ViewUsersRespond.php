<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Query\ViewUsers;

use JsonSerializable;
use PhpLab\Modules\User\Domain\Enum\UserRole;
use PhpLab\Modules\User\Domain\Enum\UserStatus;
use PhpLab\Modules\User\Domain\Model\User;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "AdminUserViewRespond",
    required: ['id', 'username', 'email', 'role', 'status'],
    properties: [
        new OA\Property(property:'id', type: 'integer'),
        new OA\Property(property:'username', type: 'string'),
        new OA\Property(property:'email', type: 'string'),
        new OA\Property(
            property:'role',
            type: 'string',
            enum: [UserRole::ADMIN, UserRole::MANAGER],
            example: UserRole::ADMIN,
        ),
        new OA\Property(
            property:'status',
            type: 'string',
            enum: [UserStatus::DISABLED, UserStatus::ACTIVE, UserStatus::DELETED],
            example: UserStatus::ACTIVE,
        ),
    ],
    type: 'object'
)]
final class ViewUsersRespond implements JsonSerializable
{
    public function __construct(
        public readonly User $user,
    ) {
    }

    public function jsonSerialize(): array
    {
        [
            $id,
            $username,
            $email,
            $role,
            $status,
        ] = $this->user->getDataForViewUserRespond();

        return [
            'id'            => $id,
            'username'      => $username,
            'email'         => $email,
            'role'          => $role,
            'status'        => $status,
        ];
    }
}