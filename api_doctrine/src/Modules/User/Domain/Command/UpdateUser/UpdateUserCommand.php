<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Command\UpdateUser;


use PhpLab\Modules\User\Domain\Enum\UserRole;
use PhpLab\Modules\User\Domain\Enum\UserStatus;

use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "AdminUserUpdateCommand",
    required: ['username', 'email', 'role', 'status'],
    properties: [
        new OA\Property(property: 'username', type: 'string', maximum: 255, minimum: 3),
        new OA\Property(property:'email', type: 'string', format: 'email'),
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
final readonly class UpdateUserCommand
{
    public function __construct(
        #[Assert\Length(min:3, max: 255)]
        #[Assert\NotBlank(message: "user.username.not_blank")]
        public string $username = '',

        #[Assert\Length(min:3, max: 255)]
        #[Assert\Email(message: "user.email.not_correct")]
        #[Assert\NotBlank(message: "user.email.not_blank")]
        public string $email = '',

        #[Assert\Choice(callback: [UserRole::class, 'toArray'])]
        #[Assert\NotBlank]
        public string $role = UserRole::MANAGER->value,

        #[Assert\Choice(callback: [UserStatus::class, 'toArray'])]
        #[Assert\NotBlank]
        public string $status = UserStatus::DISABLED->value,
    ) {
    }
}