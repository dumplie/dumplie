<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Tests\Doctrine;

use Doctrine\ORM\EntityManager;
use Dumplie\SharedKernel\Application\Extension\Command\TransactionExtension;
use Dumplie\SharedKernel\Application\EventLog;
use Dumplie\CustomerService\Domain\Order\NewOrderListener;
use Dumplie\Customer\Domain\Event\CustomerPlacedOrder;
use Dumplie\SharedKernel\Infrastructure\Doctrine\ORM\Application\Transaction\ORMFactory;
use Dumplie\CustomerService\Infrastructure\Doctrine\ORM\Domain\ORMOrders;
use Dumplie\CustomerService\Infrastructure\Doctrine\ORM\Domain\ORMPayments;
use Dumplie\CustomerService\Tests\AbstractCustomerServiceContext;
use Dumplie\SharedKernel\Tests\Context\CommandBusFactory;

final class ORMCustomerServiceContext extends AbstractCustomerServiceContext
{
    /**
     * @param EntityManager $entityManager
     * @param EventLog $eventLog
     * @param CommandBusFactory $commandBusFactory
     */
    public function __construct(EntityManager $entityManager, EventLog $eventLog, CommandBusFactory $commandBusFactory)
    {
        $this->orders = new ORMOrders($entityManager);
        $this->payments = new ORMPayments($entityManager);

        $this->transactionFactory = new ORMFactory($entityManager);
        $this->commandBus = $this->createCommandBus($commandBusFactory, [new TransactionExtension($this->transactionFactory)]);
        $this->eventLog = $eventLog;

        $this->eventLog->subscribeFor(CustomerPlacedOrder::class, new NewOrderListener($this->orders));
    }
}