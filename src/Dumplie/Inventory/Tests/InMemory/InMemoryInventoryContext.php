<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\Tests\InMemory;

use Dumplie\Inventory\Infrastructure\InMemory\InMemoryProducts;
use Dumplie\SharedKernel\Application\EventLog;
use Dumplie\Inventory\Tests\AbstractInventoryContext;
use Dumplie\SharedKernel\Tests\Context\CommandBusFactory;

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