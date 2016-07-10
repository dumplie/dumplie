<?php

declare (strict_types = 1);

namespace Dumplie\Test\Integration\Application\Doctrine\Inventory;

use Doctrine\ORM\EntityManager;
use Dumplie\Infrastructure\InMemory\InMemoryEventLog;
use Dumplie\Test\Context\Doctrine\ORMInventoryContext;
use Dumplie\Test\Context\Tactician\TacticianFactory;
use Dumplie\Test\Doctrine\ORMHelper;
use Dumplie\Test\Integration\Application\Generic\Inventory\ProductTestCase;

final class ProductTest extends ProductTestCase
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

        $this->inventoryContext = new ORMInventoryContext($this->entityManager, new InMemoryEventLog(), new TacticianFactory());
    }

    protected function clear()
    {
        $this->entityManager->clear();
    }

    public function tearDown()
    {
        $this->dropSchema($this->entityManager);
    }
}