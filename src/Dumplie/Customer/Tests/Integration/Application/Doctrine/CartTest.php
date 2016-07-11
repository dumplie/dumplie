<?php

namespace Dumplie\Customer\Tests\Integration\Application\Doctrine;

use Doctrine\ORM\EntityManager;
use Dumplie\Inventory\Tests\Doctrine\ORMInventoryContext;
use Dumplie\Inventory\Tests\Doctrine\ORMInventoryMapping;
use Dumplie\SharedKernel\Infrastructure\Doctrine\ORM\Application\Transaction\ORMFactory;
use Dumplie\SharedKernel\Infrastructure\InMemory\InMemoryEventLog;
use Dumplie\Customer\Tests\Doctrine\ORMCustomerContext;
use Dumplie\Customer\Tests\Doctrine\ORMCustomerMapping;
use Dumplie\Customer\Tests\Integration\Application\Generic\CartTestCase;
use Dumplie\SharedKernel\Tests\Context\Tactician\TacticianFactory;
use Dumplie\SharedKernel\Tests\Doctrine\ORMHelper;

class CartTest extends CartTestCase
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

    public function setUp()
    {
        $emBuilder = $this->entityManagerBuilder();
        $this->registerCustomerMapping($emBuilder);
        $this->registerInventoryMapping($emBuilder);

        $this->entityManager = $emBuilder->build();
        $this->createSchema($this->entityManager);

        $this->transactionFactory = new ORMFactory($this->entityManager);

        $eventLog = new InMemoryEventLog();

        $this->customerContext = new ORMCustomerContext($this->entityManager, $eventLog, new TacticianFactory());
        $inventoryContext = new ORMInventoryContext($this->entityManager, $eventLog, new TacticianFactory());

        $inventoryContext->addProduct("SKU_1", 1000);
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