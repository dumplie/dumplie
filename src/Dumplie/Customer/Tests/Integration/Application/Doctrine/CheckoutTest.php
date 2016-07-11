<?php

namespace Dumplie\Customer\Tests\Integration\Application\Doctrine;

use Doctrine\ORM\EntityManager;
use Dumplie\SharedKernel\Infrastructure\Doctrine\ORM\Application\Transaction\ORMFactory;
use Dumplie\SharedKernel\Infrastructure\InMemory\InMemoryEventLog;
use Dumplie\Customer\Tests\Doctrine\ORMCustomerContext;
use Dumplie\Customer\Tests\Doctrine\ORMCustomerMapping;
use Dumplie\Customer\Tests\Integration\Application\Generic\CheckoutTestCase;
use Dumplie\SharedKernel\Tests\Context\Tactician\TacticianFactory;
use Dumplie\SharedKernel\Tests\Doctrine\ORMHelper;

class CheckoutTest extends CheckoutTestCase
{
    use ORMHelper;
    use ORMCustomerMapping;

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

        $this->entityManager = $emBuilder->build();
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