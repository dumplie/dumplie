<?php

namespace Dumplie\CustomerService\Tests\Integration\Application\InMemory;

use Dumplie\CustomerService\Domain\Order;
use Dumplie\SharedKernel\Infrastructure\InMemory\InMemoryEventLog;
use Dumplie\CustomerService\Tests\InMemory\InMemoryCustomerServiceContext;
use Dumplie\CustomerService\Tests\Integration\Application\Generic\PaymentTestCase;
use Dumplie\SharedKernel\Tests\Context\Tactician\TacticianFactory;

class PaymentTest extends PaymentTestCase
{
    function setUp()
    {
        $this->customerServiceContext = new InMemoryCustomerServiceContext(new InMemoryEventLog(), new TacticianFactory());
    }

    protected function clear()
    {
    }
}
