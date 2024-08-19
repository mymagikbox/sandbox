<?php
declare(strict_types=1);

namespace PhpLab\Domain\Event;

use PhpLab\Domain\Interface\Event\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcher as BaseEventDispatcher;

class EventDispatcher extends BaseEventDispatcher implements EventDispatcherInterface
{

}