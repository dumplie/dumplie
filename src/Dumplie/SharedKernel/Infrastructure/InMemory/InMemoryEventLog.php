<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Infrastructure\InMemory;

use Dumplie\SharedKernel\Application\EventLog;
use Dumplie\Customer\Domain\Event\CustomerPlacedOrder;
use Dumplie\SharedKernel\Domain\Event\Event;
use Dumplie\SharedKernel\Domain\Event\Events;
use Dumplie\SharedKernel\Domain\Event\Listener;
use Dumplie\SharedKernel\Domain\Exception\UnknownEventException;

final class InMemoryEventLog implements EventLog
{
    /**
     * @var array
     */
    private $listeners;

    public function __construct()
    {
        $this->listeners = [];
    }

    /**
     * @param Event $event
     */
    public function log(Event $event)
    {
        foreach ($this->listeners as $eventClass => $listeners) {
            if ($eventClass === get_class($event)) {
                foreach ($listeners as $listener) {
                    $listener->on($this->serizlize($event));
                }
            }
        }
    }

    /**
     * @param string $eventName
     * @param Listener $listener
     */
    public function subscribeFor(string $eventName, Listener $listener)
    {
        if (!array_key_exists($eventName, $this->listeners)) {
            $this->listeners[$eventName] = [];
        }

        $this->listeners[$eventName][] = $listener;
    }

    /**
     * @param Event $event
     * @return string
     * @throws UnknownEventException
     */
    private function serizlize(Event $event)
    {
        if ($event instanceof CustomerPlacedOrder) {
            return json_encode([
                'name' => Events::CUSTOMER_PLACED_ORDER,
                'order_id' => $event->orderId(),
                'date' => $event->date()->format('Y-m-d H:i:s')
            ]);
        }

        throw UnknownEventException::unsupported(get_class($event));
    }
}