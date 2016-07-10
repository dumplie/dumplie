<?php

declare (strict_types = 1);

namespace Dumplie\Test\Context\Memory;

use Dumplie\Application\EventLog;
use Dumplie\Domain\CustomerService\Order\NewOrderListener;
use Dumplie\Domain\Customer\Event\CustomerPlacedOrder;
use Dumplie\Infrastructure\InMemory\CustomerService\InMemoryOrders;
use Dumplie\Infrastructure\InMemory\CustomerService\InMemoryPayments;
use Dumplie\Infrastructure\InMemory\Transaction\Factory;
use Dumplie\Test\Context\AbstractCustomerServiceContext;
use Dumplie\Test\Context\CommandBusFactory;

final class InMemoryCustomerServiceContext extends AbstractCustomerServiceContext
{
    /**
     * @param EventLog $eventLog
     * @param CommandBusFactory $commandBusFactory
     */
    public function __construct(EventLog $eventLog, CommandBusFactory $commandBusFactory)
    {
        $this->orders = new InMemoryOrders();
        $this->payments = new InMemoryPayments();

        $this->transactionFactory = new Factory();
        $this->commandBus = $this->createCommandBus($commandBusFactory);
        $this->eventLog = $eventLog;

        $this->eventLog->subscribeFor(CustomerPlacedOrder::class, new NewOrderListener($this->orders));
    }
}