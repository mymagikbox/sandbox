<?php
declare(strict_types=1);

namespace PhpLab\Domain\Event;

interface EventListenerInterface
{
    public function handle(Event $event): void;
}