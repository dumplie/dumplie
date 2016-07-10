<?php

declare (strict_types = 1);

namespace Dumplie\Test\Context\Memory;

use Dumplie\Application\EventLog;
use Dumplie\Infrastructure\InMemory\Customer\InMemoryCarts;
use Dumplie\Infrastructure\InMemory\Customer\InMemoryCheckouts;
use Dumplie\Infrastructure\InMemory\Customer\InMemoryOrders;
use Dumplie\Infrastructure\InMemory\Customer\InMemoryProducts;
use Dumplie\Test\Context\AbstractCustomerContext;
use Dumplie\Test\Context\CommandBusFactory;

final class InMemoryCustomerContext extends AbstractCustomerContext
{
    /**
     * @param EventLog $eventLog
     * @param CommandBusFactory $commandBusFactory
     * @param array $products
     */
    public function __construct(EventLog $eventLog, CommandBusFactory $commandBusFactory, array $products = [])
    {
        $this->carts = new InMemoryCarts();
        $this->checkouts = new InMemoryCheckouts();
        $this->orders = new InMemoryOrders();
        $this->products = new InMemoryProducts($products);
        $this->commandBus = $this->createCommandBus($eventLog, $commandBusFactory);
    }
}