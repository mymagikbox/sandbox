<?php
declare(strict_types=1);

namespace PhpLab\Domain\Interface\Event;

use PhpLab\Domain\Event\Event;

interface EventListenerInterface
{
    public function handle(Event $event): void;
}