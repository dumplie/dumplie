<?php

declare (strict_types = 1);

namespace Dumplie\Test\Integration\Application\Doctrine\Customer;

use Doctrine\ORM\EntityManager;
use Dumplie\Application\Command\Extension\Core\TransactionExtension;
use Dumplie\Infrastructure\Doctrine\ORM\Implementation\Application\Transaction\ORMFactory;
use Dumplie\Infrastructure\InMemory\InMemoryEventLog;
use Dumplie\Test\Context\Doctrine\ORMCustomerContext;
use Dumplie\Test\Context\Doctrine\ORMInventoryContext;
use Dumplie\Test\Context\Tactician\TacticianFactory;
use Dumplie\Test\Doctrine\ORMHelper;
use Dumplie\Test\Integration\Application\Generic\Customer\OrderTestCase;

final class OrderTest extends OrderTestCase
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

    function setUp()
    {
        $this->entityManager = $this->createEntityManager();
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