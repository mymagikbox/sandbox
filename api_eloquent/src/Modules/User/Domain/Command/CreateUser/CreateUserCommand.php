<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Command\CreateUser;

use PhpLab\Modules\User\Domain\Entity\Role;
use PhpLab\Modules\User\Domain\Entity\Status;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateUserCommand
{
    public function __construct(
        #[Assert\Length(min:3, max: 255)]
        #[Assert\Regex(pattern:"/^(\w+)$/", message: "user.username.not_correct")]
        #[Assert\NotBlank(message: "user.username.not_blank")]
        public readonly string $username = '',

        #[Assert\Length(min: 6, max: 255)]
        #[Assert\NotBlank(message: "user.password.not_blank")]
        public readonly string $password = '',

        #[Assert\Choice(choices: Role::ALL_ROLES)]
        #[Assert\NotBlank]
        public readonly string $role = '',

        #[Assert\Choice(choices: Status::ALL_STATUS_LIST)]
        #[Assert\NotBlank]
        public readonly string $status = Status::STATUS_UNCONFIRMED,
    ) {
    }
}