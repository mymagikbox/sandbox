<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Query\ListUsers;

use JsonSerializable;
use PhpLab\Domain\Helper\DateTimeHelper;
use PhpLab\Modules\User\Domain\Model\User;

final class ListUsersCollection implements JsonSerializable
{
    private array $collection = [];

    public static function create(?array $collection): self
    {
        $instance = new self();

        if (count($collection)) {
            $instance->collection = $collection;
        }

        return $instance;
    }

    public function add(User $user): void
    {
        $this->collection[] = $user;
    }

    public function jsonSerialize(): array
    {
        $result = [];

        /** @var $item User */
        foreach ($this->collection as $item) {
            $result[] = [
                'id'            => $item->getId(),
                'username'      => $item->getUsername(),
                'role'          => $item->getRole()->getValue(),
                'status'        => $item->getStatus()->getValue(),
                'created_at'    => DateTimeHelper::format($item->getCreatedAt()),
                'updated_at'    => DateTimeHelper::format($item->getUpdatedAt()),
                'deleted_at'    => DateTimeHelper::format($item->getDeletedAt()),
            ];
        }

        return $result;
    }
}