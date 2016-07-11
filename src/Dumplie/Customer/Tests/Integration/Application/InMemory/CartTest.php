<?php

namespace Dumplie\Customer\Tests\Integration\Application\InMemory;

use Dumplie\Customer\Domain\Product;
use Dumplie\SharedKernel\Domain\Money\Price;
use Dumplie\SharedKernel\Domain\Product\SKU;
use Dumplie\SharedKernel\Infrastructure\InMemory\InMemoryEventLog;
use Dumplie\SharedKernel\Infrastructure\InMemory\Transaction\Factory;
use Dumplie\Customer\Tests\InMemory\InMemoryCustomerContext;
use Dumplie\Customer\Tests\Integration\Application\Generic\CartTestCase;
use Dumplie\SharedKernel\Tests\Context\Tactician\TacticianFactory;

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
