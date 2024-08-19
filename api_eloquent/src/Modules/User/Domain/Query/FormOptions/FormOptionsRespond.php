<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Query\FormOptions;

use JsonSerializable;
use PhpLab\Modules\User\Domain\Entity\Role;
use PhpLab\Modules\User\Domain\Entity\Status;

final class FormOptionsRespond implements JsonSerializable
{
    private Role $role;
    private Status $status;

    public function __construct(
    )
    {
        $this->role = Role::create(Role::ROLE_MANAGER);
        $this->status = Status::create(Status::STATUS_UNCONFIRMED);
    }

    public function jsonSerialize(): array
    {
        return [
            'role'  => [
                'default' => Role::ROLE_MANAGER,
                'options' => $this->role->getOptionList()
            ],
            'status' => [
                'default' => Status::STATUS_ACTIVE,
                'options' => $this->status->getOptionList()
            ],
        ];
    }
}