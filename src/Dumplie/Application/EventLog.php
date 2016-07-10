<?php

declare (strict_types = 1);

namespace Dumplie\Application;

use Dumplie\Domain\SharedKernel\Event\Event;
use Dumplie\Domain\SharedKernel\Event\Listener;

interface EventLog
{
    /**
     * @param Event $event
     */
    public function log(Event $event);

    /**
     * @param string $eventName
     * @param Listener $listener
     */
    public function subscribeFor(string $eventName, Listener $listener);
}