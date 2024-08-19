<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Query\ViewUsers;

use JsonSerializable;
use PhpLab\Modules\User\Domain\User;

final class ViewUsersRespond implements JsonSerializable
{
    public function __construct(
        public readonly User $user,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id'            => $this->user->getId(),
            'username'      => $this->user->getUsername(),
            'role'          => $this->user->getRole()->getValue(),
            //'status'        => $item->getStatus()->getJsonValue(),
            'status'        => $this->user->getStatus()->getValue(),
        ];
    }
}