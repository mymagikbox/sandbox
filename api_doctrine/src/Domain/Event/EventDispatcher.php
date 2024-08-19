<?php
declare(strict_types=1);

namespace PhpLab\Domain\Event;

use Symfony\Component\EventDispatcher\EventDispatcher as BaseEventDispatcher;

class EventDispatcher extends BaseEventDispatcher implements EventDispatcherInterface
{

}