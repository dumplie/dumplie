<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\Tests\Doctrine;

use Doctrine\ORM\EntityManager;
use Dumplie\SharedKernel\Application\Command\Extension\Core\TransactionExtension;
use Dumplie\SharedKernel\Application\EventLog;
use Dumplie\SharedKernel\Infrastructure\Doctrine\ORM\Application\Transaction\ORMFactory;
use Dumplie\Inventory\Infrastructure\Doctrine\ORM\Domain\ORMProducts;
use Dumplie\Inventory\Tests\AbstractInventoryContext;
use Dumplie\SharedKernel\Tests\Context\CommandBusFactory;

final class ORMInventoryContext extends AbstractInventoryContext
{
    /**
     * @param EntityManager $entityManager
     * @param EventLog $eventLog
     */
    public function __construct(EntityManager $entityManager, EventLog $eventLog, CommandBusFactory $commandBusFactory)
    {
        $this->products = new ORMProducts($entityManager);
        $this->commandBus = $this->createCommandBus($commandBusFactory, $eventLog, [new TransactionExtension(new ORMFactory($entityManager))]);
    }
}