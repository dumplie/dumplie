<?php

namespace Dumplie\CustomerService\Tests\Integration\Application\InMemory;

use Dumplie\CustomerService\Domain\Order;
use Dumplie\SharedKernel\Infrastructure\InMemory\InMemoryEventLog;
use Dumplie\CustomerService\Tests\InMemory\InMemoryCustomerServiceContext;
use Dumplie\CustomerService\Tests\Integration\Application\Generic\OrderTestCase;
use Dumplie\SharedKernel\Tests\Context\Tactician\TacticianFactory;

class OrderTest extends OrderTestCase
{
    function setUp()
    {
        $this->customerServiceContext = new InMemoryCustomerServiceContext(new InMemoryEventLog(), new TacticianFactory());
    }
}
