<?php

namespace Dumplie\Test\Integration\Application\InMemory\Customer;

use Dumplie\Domain\Customer\Product;
use Dumplie\Domain\SharedKernel\Money\Price;
use Dumplie\Domain\SharedKernel\Product\SKU;
use Dumplie\Infrastructure\InMemory\InMemoryEventLog;
use Dumplie\Infrastructure\InMemory\Transaction\Factory;
use Dumplie\Test\Context\Memory\InMemoryCustomerContext;
use Dumplie\Test\Context\Tactician\TacticianFactory;
use Dumplie\Test\Integration\Application\Generic\Customer\CartTestCase;

class CartTest extends CartTestCase
{
    function setUp()
    {
        $this->transactionFactory = new Factory();

        $eventLog = new InMemoryEventLog();
        $this->customerContext = new InMemoryCustomerContext($eventLog, new TacticianFactory(), [
            new Product(new SKU('SKU_1'), Price::EUR(2500), true),
            new Product(new SKU('SKU_2'), Price::EUR(2500), true)
        ]);
    }

    public function clear()
    {
    }
}
