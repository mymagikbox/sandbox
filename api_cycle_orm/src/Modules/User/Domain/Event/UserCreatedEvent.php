<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Event;

use PhpLab\Domain\Event\Event;
use PhpLab\Modules\User\Domain\Model\User;

final class UserCreatedEvent extends Event
{
    public const NAME = 'user.created';

    public function __construct(
        public readonly User $user
    )
    {
    }
}