<?php

namespace Dumplie\Test\Integration\Application\InMemory\CustomerService;

use Dumplie\Domain\CustomerService\Order;
use Dumplie\Infrastructure\InMemory\InMemoryEventLog;
use Dumplie\Test\Context\Memory\InMemoryCustomerServiceContext;
use Dumplie\Test\Context\Tactician\TacticianFactory;
use Dumplie\Test\Integration\Application\Generic\CustomerService\OrderTestCase;

class OrderTest extends OrderTestCase
{
    function setUp()
    {
        $this->customerServiceContext = new InMemoryCustomerServiceContext(new InMemoryEventLog(), new TacticianFactory());
    }
}
