<?php

namespace Dumplie\Test\Integration\Application\InMemory\Inventory;

use Dumplie\Infrastructure\InMemory\InMemoryEventLog;
use Dumplie\Test\Context\Memory\InMemoryInventoryContext;
use Dumplie\Test\Context\Tactician\TacticianFactory;
use Dumplie\Test\Integration\Application\Generic\Inventory\ProductTestCase;

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
