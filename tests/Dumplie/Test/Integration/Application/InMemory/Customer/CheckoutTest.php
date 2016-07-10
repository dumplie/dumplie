<?php

namespace Dumplie\Test\Integration\Application\InMemory\Customer;

use Dumplie\Infrastructure\InMemory\InMemoryEventLog;
use Dumplie\Infrastructure\InMemory\Transaction\Factory;
use Dumplie\Test\Context\Memory\InMemoryCustomerContext;
use Dumplie\Test\Context\Tactician\TacticianFactory;
use Dumplie\Test\Integration\Application\Generic\Customer\CheckoutTestCase;

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
