<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application;

use Dumplie\SharedKernel\Domain\Event\Event;
use Dumplie\SharedKernel\Domain\Event\Listener;

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