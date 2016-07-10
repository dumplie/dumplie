<?php

declare (strict_types = 1);

namespace Dumplie\Test\Integration\Application\Doctrine\CustomerService;

use Doctrine\ORM\EntityManager;
use Dumplie\Infrastructure\InMemory\InMemoryEventLog;
use Dumplie\Test\Context\Doctrine\ORMCustomerServiceContext;
use Dumplie\Test\Context\Tactician\TacticianFactory;
use Dumplie\Test\Doctrine\ORMHelper;
use Dumplie\Test\Integration\Application\Generic\CustomerService\OrderTestCase;

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

        $this->customerServiceContext = new ORMCustomerServiceContext($this->entityManager, new InMemoryEventLog(), new TacticianFactory());
    }

    public function tearDown()
    {
        $this->dropSchema($this->entityManager);
    }
}