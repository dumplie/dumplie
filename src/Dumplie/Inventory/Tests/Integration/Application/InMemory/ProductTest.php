<?php

namespace Dumplie\Inventory\Tests\Integration\Application\InMemory;

use Dumplie\SharedKernel\Infrastructure\InMemory\InMemoryEventLog;
use Dumplie\Inventory\Tests\InMemory\InMemoryInventoryContext;
use Dumplie\Inventory\Tests\Integration\Application\Generic\ProductTestCase;
use Dumplie\SharedKernel\Tests\Context\Tactician\TacticianFactory;

class ProductTest extends ProductTestCase
{
    function setUp()
    {
        $this->inventoryContext = new InMemoryInventoryContext(new TacticianFactory(), new InMemoryEventLog());
    }

    protected function clear()
    {
    }
}
