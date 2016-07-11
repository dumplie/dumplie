<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Tests\Doctrine;

use Doctrine\ORM\EntityManager;
use Dumplie\Customer\Tests\AbstractCustomerContext;
use Dumplie\SharedKernel\Application\Command\Extension\Core\TransactionExtension;
use Dumplie\SharedKernel\Application\EventLog;
use Dumplie\Customer\Infrastructure\Doctrine\Dbal\Domain\DbalProducts;
use Dumplie\SharedKernel\Infrastructure\Doctrine\ORM\Application\Transaction\ORMFactory;
use Dumplie\Customer\Infrastructure\Doctrine\ORM\Domain\ORMCarts;
use Dumplie\Customer\Infrastructure\Doctrine\ORM\Domain\ORMCheckouts;
use Dumplie\Customer\Infrastructure\Doctrine\ORM\Domain\ORMOrders;
use Dumplie\SharedKernel\Tests\Context\CommandBusFactory;

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