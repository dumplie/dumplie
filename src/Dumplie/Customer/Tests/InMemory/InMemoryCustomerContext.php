<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Tests\InMemory;

use Dumplie\SharedKernel\Application\EventLog;
use Dumplie\Customer\Infrastructure\InMemory\InMemoryCarts;
use Dumplie\Customer\Infrastructure\InMemory\InMemoryCheckouts;
use Dumplie\Customer\Infrastructure\InMemory\InMemoryOrders;
use Dumplie\Customer\Infrastructure\InMemory\InMemoryProducts;
use Dumplie\Customer\Tests\AbstractCustomerContext;
use Dumplie\SharedKernel\Tests\Context\CommandBusFactory;

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