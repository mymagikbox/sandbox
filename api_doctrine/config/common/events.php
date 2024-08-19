<?php
declare(strict_types=1);

use Psr\Container\ContainerInterface;
use PhpLab\Domain\Event\EventDispatcher;
use PhpLab\Domain\Event\EventDispatcherInterface;
use PhpLab\Modules\User\Domain\Event\UserCreatedEvent;
use PhpLab\Modules\Mail\Infrastructure\EventListener\UserCreatedEventListener;


return [
    EventDispatcherInterface::class => function (ContainerInterface $c) {
        $dispatcher = new EventDispatcher();

        $dispatcher->addListener(UserCreatedEvent::NAME, [$c->get(UserCreatedEventListener::class), 'handle']);

        return $dispatcher;
    },
];