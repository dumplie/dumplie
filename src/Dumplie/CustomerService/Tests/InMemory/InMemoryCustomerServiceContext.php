<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Tests\InMemory;

use Dumplie\SharedKernel\Application\EventLog;
use Dumplie\CustomerService\Domain\Order\NewOrderListener;
use Dumplie\Customer\Domain\Event\CustomerPlacedOrder;
use Dumplie\CustomerService\Infrastructure\InMemory\InMemoryOrders;
use Dumplie\CustomerService\Infrastructure\InMemory\InMemoryPayments;
use Dumplie\SharedKernel\Infrastructure\InMemory\Transaction\Factory;
use Dumplie\CustomerService\Tests\AbstractCustomerServiceContext;
use Dumplie\SharedKernel\Tests\Context\CommandBusFactory;

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