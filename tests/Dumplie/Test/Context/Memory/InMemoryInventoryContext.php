<?php

declare (strict_types = 1);

namespace Dumplie\Test\Context\Memory;

use Dumplie\Application\EventLog;
use Dumplie\Infrastructure\InMemory\Inventory\InMemoryProducts;
use Dumplie\Test\Context\AbstractInventoryContext;
use Dumplie\Test\Context\CommandBusFactory;

final class InMemoryInventoryContext extends AbstractInventoryContext
{
    /**
     * @param CommandBusFactory $commandBusFactory
     * @param EventLog $eventLog
     */
    public function __construct(CommandBusFactory $commandBusFactory, EventLog $eventLog)
    {
        $this->products = new InMemoryProducts();
        $this->commandBus = $this->createCommandBus($commandBusFactory, $eventLog);
    }
}