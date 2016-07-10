<?php

declare (strict_types = 1);

namespace Dumplie\Test\Context\Doctrine;

use Doctrine\ORM\EntityManager;
use Dumplie\Application\Command\Extension\Core\TransactionExtension;
use Dumplie\Application\EventLog;
use Dumplie\Infrastructure\Doctrine\Dbal\Implementation\Domain\Customer\DbalProducts;
use Dumplie\Infrastructure\Doctrine\ORM\Implementation\Application\Transaction\ORMFactory;
use Dumplie\Infrastructure\Doctrine\ORM\Implementation\Domain\Customer\ORMCarts;
use Dumplie\Infrastructure\Doctrine\ORM\Implementation\Domain\Customer\ORMCheckouts;
use Dumplie\Infrastructure\Doctrine\ORM\Implementation\Domain\Customer\ORMOrders;
use Dumplie\Test\Context\AbstractCustomerContext;
use Dumplie\Test\Context\CommandBusFactory;

final class ORMCustomerContext extends AbstractCustomerContext
{
    /**
     * @param EntityManager $entityManager
     * @param EventLog $eventLog
     * @param CommandBusFactory $commandBusFactory
     */
    public function __construct(EntityManager $entityManager, EventLog $eventLog, CommandBusFactory $commandBusFactory)
    {
        $this->carts = new ORMCarts($entityManager);
        $this->products = new DbalProducts($entityManager->getConnection());
        $this->checkouts = new ORMCheckouts($entityManager);
        $this->orders = new ORMOrders($entityManager);
        $this->commandBus = $this->createCommandBus($eventLog, $commandBusFactory, [new TransactionExtension(new ORMFactory($entityManager))]);
    }
}