<?php

namespace Dumplie\Test\Integration\Application\Doctrine\Customer;

use Doctrine\ORM\EntityManager;
use Dumplie\Infrastructure\Doctrine\ORM\Implementation\Application\Transaction\ORMFactory;
use Dumplie\Infrastructure\InMemory\InMemoryEventLog;
use Dumplie\Test\Context\Doctrine\ORMCustomerContext;
use Dumplie\Test\Context\Tactician\TacticianFactory;
use Dumplie\Test\Doctrine\ORMHelper;
use Dumplie\Test\Integration\Application\Generic\Customer\CheckoutTestCase;

class CheckoutTest extends CheckoutTestCase
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

        $this->customerContext = new ORMCustomerContext($this->entityManager, new InMemoryEventLog(), new TacticianFactory());
        $this->transactionFactory = new ORMFactory($this->entityManager);
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