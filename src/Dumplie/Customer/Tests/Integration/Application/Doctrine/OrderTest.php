<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Tests\Integration\Application\Doctrine;

use Doctrine\ORM\EntityManager;
use Dumplie\Inventory\Tests\Doctrine\ORMInventoryContext;
use Dumplie\Inventory\Tests\Doctrine\ORMInventoryMapping;
use Dumplie\SharedKernel\Infrastructure\Doctrine\ORM\Application\Transaction\ORMFactory;
use Dumplie\SharedKernel\Infrastructure\InMemory\InMemoryEventLog;
use Dumplie\Customer\Tests\Doctrine\ORMCustomerContext;
use Dumplie\Customer\Tests\Doctrine\ORMCustomerMapping;
use Dumplie\Customer\Tests\Integration\Application\Generic\OrderTestCase;
use Dumplie\SharedKernel\Tests\Context\Tactician\TacticianFactory;
use Dumplie\SharedKernel\Tests\Doctrine\ORMHelper;

final class OrderTest extends OrderTestCase
{
    use ORMHelper;
    use ORMCustomerMapping;
    use ORMInventoryMapping;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public static function setUpBeforeClass()
    {
        self::createDatabase();
    }

    function setUp()
    {
        $emBuilder = $this->entityManagerBuilder();
        $this->registerCustomerMapping($emBuilder);
        $this->registerInventoryMapping($emBuilder);

        $this->entityManager = $emBuilder->build();
        $this->createSchema($this->entityManager);
        $this->eventLog = new InMemoryEventLog();
        $this->transactionFactory = new ORMFactory($this->entityManager);

        $this->customerContext = new ORMCustomerContext($this->entityManager, $this->eventLog, new TacticianFactory());
        $inventoryContext = new ORMInventoryContext($this->entityManager, $this->eventLog, new TacticianFactory());

        $inventoryContext->addProduct('SKU_1', 2500);
        $inventoryContext->addProduct('SKU_2', 2500);
    }

    public function clear()
    {
        $this->entityManager->clear();
    }

    public function tearDown()
    {
        $this->dropSchema($this->entityManager);
    }
}