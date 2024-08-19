<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Query\ListUsers;

use PhpLab\Modules\User\Domain\Model\Entity\Role;
use PhpLab\Modules\User\Domain\Model\Entity\Status;
use Symfony\Component\Validator\Constraints as Assert;

final class ListUsersFilter
{
    public function __construct(
        #[Assert\Length(min:3, max: 255)]
        #[Assert\Regex(pattern:"/^(\w+)$/", message: "user.username.not_correct")]
        public readonly ?string $username,

        #[Assert\Choice(choices: Role::ALL_ROLES)]
        public readonly ?string $role,

        #[Assert\Choice(choices: Status::ALL_STATUS_LIST)]
        public readonly ?string $status,
    )
    {
    }

    public static function create(array $data): self
    {
        $username = $data['username'] ?? null;
        $role = $data['role'] ?? null;
        $status = $data['status'] ?? null;

        return new self($username, $role, $status);
    }
}