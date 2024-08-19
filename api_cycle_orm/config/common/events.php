<?php
declare(strict_types=1);

use PhpLab\Domain\Event\EventDispatcher;
use PhpLab\Domain\Interface\Event\EventDispatcherInterface;
use PhpLab\Modules\Mail\Infrastructure\EventListener\UserCreatedEventListener;
use PhpLab\Modules\User\Domain\Event\UserCreatedEvent;
use Psr\Container\ContainerInterface;


return [
    EventDispatcherInterface::class => function (ContainerInterface $c) {
        $dispatcher = new EventDispatcher();

        $dispatcher->addListener(UserCreatedEvent::NAME, [$c->get(UserCreatedEventListener::class), 'handle']);

        return $dispatcher;
    },
];