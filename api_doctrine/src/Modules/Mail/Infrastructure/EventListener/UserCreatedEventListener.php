<?php
declare(strict_types=1);

namespace PhpLab\Modules\Mail\Infrastructure\EventListener;

use PhpLab\Domain\Event\Event;
use PhpLab\Domain\Event\EventListenerInterface;
use PhpLab\Modules\User\Domain\Event\UserCreatedEvent;
use Psr\Log\LoggerInterface;

final class UserCreatedEventListener implements EventListenerInterface
{
    public function __construct(
        private readonly LoggerInterface $logger
    )
    {
    }

    public function handle(UserCreatedEvent|Event $event): void
    {
        $this->logger->debug('Test UserCreatedEvent: ' . $event->user->getEmail());

        // TODO: Implement handle() method.
    }
}