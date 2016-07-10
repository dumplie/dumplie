<?php

declare (strict_types = 1);

namespace Dumplie\Test\Context\Doctrine;

use Doctrine\ORM\EntityManager;
use Dumplie\Application\Command\Extension\Core\TransactionExtension;
use Dumplie\Application\EventLog;
use Dumplie\Domain\CustomerService\Order\NewOrderListener;
use Dumplie\Domain\Customer\Event\CustomerPlacedOrder;
use Dumplie\Infrastructure\Doctrine\ORM\Implementation\Application\Transaction\ORMFactory;
use Dumplie\Infrastructure\Doctrine\ORM\Implementation\Domain\CustomerService\ORMOrders;
use Dumplie\Infrastructure\Doctrine\ORM\Implementation\Domain\CustomerService\ORMPayments;
use Dumplie\Test\Context\AbstractCustomerServiceContext;
use Dumplie\Test\Context\CommandBusFactory;

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