<?php

namespace Dumplie\Test\Integration\Application\Doctrine\Customer;

use Doctrine\ORM\EntityManager;
use Dumplie\Infrastructure\Doctrine\ORM\Implementation\Application\Transaction\ORMFactory;
use Dumplie\Infrastructure\InMemory\InMemoryEventLog;
use Dumplie\Test\Context\Doctrine\ORMCustomerContext;
use Dumplie\Test\Context\Doctrine\ORMInventoryContext;
use Dumplie\Test\Context\Tactician\TacticianFactory;
use Dumplie\Test\Doctrine\ORMHelper;
use Dumplie\Test\Integration\Application\Generic\Customer\CartTestCase;

class CartTest extends CartTestCase
{
    use ORMHelper;

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
        $this->entityManager = $this->createEntityManager();
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