<?php

namespace Dumplie\Customer\Tests\Integration\Application\InMemory;

use Dumplie\SharedKernel\Infrastructure\InMemory\InMemoryEventLog;
use Dumplie\SharedKernel\Infrastructure\InMemory\Transaction\Factory;
use Dumplie\Customer\Tests\InMemory\InMemoryCustomerContext;
use Dumplie\Customer\Tests\Integration\Application\Generic\CheckoutTestCase;
use Dumplie\SharedKernel\Tests\Context\Tactician\TacticianFactory;

class CheckoutTest extends CheckoutTestCase
{
    function setUp()
    {
        $this->transactionFactory = new Factory();
        $this->customerContext = new InMemoryCustomerContext(new InMemoryEventLog(), new TacticianFactory());
    }

    public function clear()
    {
    }
}
