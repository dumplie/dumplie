<?php

declare (strict_types = 1);

namespace Dumplie\Test\Context\Doctrine;

use Doctrine\ORM\EntityManager;
use Dumplie\Application\Command\Extension\Core\TransactionExtension;
use Dumplie\Application\Command\Inventory\CreateProduct;
use Dumplie\Application\Command\Inventory\CreateProductHandler;
use Dumplie\Application\Command\Inventory\PutBackProductToStock;
use Dumplie\Application\Command\Inventory\PutBackProductToStockHandler;
use Dumplie\Application\Command\Inventory\RemoveProductFromStock;
use Dumplie\Application\Command\Inventory\RemoveProductFromStockHandler;
use Dumplie\Application\CommandBus;
use Dumplie\Application\EventLog;
use Dumplie\Domain\Inventory\Products;
use Dumplie\Infrastructure\Doctrine\ORM\Implementation\Application\Transaction\ORMFactory;
use Dumplie\Infrastructure\Doctrine\ORM\Implementation\Domain\Inventory\ORMProducts;
use Dumplie\Test\Context\AbstractInventoryContext;
use Dumplie\Test\Context\CommandBusFactory;
use Dumplie\Test\Context\InventoryContext;

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