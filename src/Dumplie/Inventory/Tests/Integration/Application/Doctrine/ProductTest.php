<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\Tests\Integration\Application\Doctrine;

use Doctrine\ORM\EntityManager;
use Dumplie\SharedKernel\Infrastructure\InMemory\InMemoryEventLog;
use Dumplie\Inventory\Tests\Doctrine\ORMInventoryContext;
use Dumplie\Inventory\Tests\Doctrine\ORMInventoryMapping;
use Dumplie\Inventory\Tests\Integration\Application\Generic\ProductTestCase;
use Dumplie\SharedKernel\Tests\Context\Tactician\TacticianFactory;
use Dumplie\SharedKernel\Tests\Doctrine\ORMHelper;

final class ProductTest extends ProductTestCase
{
    use ORMHelper;
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
        $this->registerInventoryMapping($emBuilder);

        $this->entityManager = $emBuilder->build();
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