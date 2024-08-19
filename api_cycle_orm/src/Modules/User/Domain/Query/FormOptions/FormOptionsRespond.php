<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Query\FormOptions;

use JsonSerializable;
use PhpLab\Modules\User\Domain\Enum\UserRole;
use PhpLab\Modules\User\Domain\Enum\UserStatus;

final class FormOptionsRespond implements JsonSerializable
{
    public function jsonSerialize(): array
    {
        return [
            'role'  => [
                'default' => UserRole::MANAGER,
                'options' => UserRole::getOptionList()
            ],
            'status' => [
                'default' => UserStatus::ACTIVE,
                'options' => UserStatus::getOptionList()
            ],
        ];
    }
}