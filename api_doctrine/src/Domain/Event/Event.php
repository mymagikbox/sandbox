<?php
declare(strict_types=1);

namespace PhpLab\Domain\Event;

use Symfony\Contracts\EventDispatcher\Event as BaseEvent;

class Event extends BaseEvent
{
    public const NAME = 'rename.me';
}