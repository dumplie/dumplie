<?php

declare (strict_types = 1);

namespace Dumplie\Test\Integration\Application\InMemory\Customer;

use Dumplie\Domain\Customer\Product;
use Dumplie\Domain\SharedKernel\Money\Price;
use Dumplie\Domain\SharedKernel\Product\SKU;
use Dumplie\Infrastructure\InMemory\InMemoryEventLog;
use Dumplie\Infrastructure\InMemory\Transaction\Factory;
use Dumplie\Test\Context\Tactician\TacticianFactory;
use Dumplie\Test\Integration\Application\Generic\Customer\OrderTestCase;
use Dumplie\Test\Context\Memory\InMemoryCustomerContext;

final class OrderTest extends OrderTestCase
{
    function setUp()
    {
        $this->eventLog = new InMemoryEventLog();
        $this->transactionFactory = new Factory();

        $this->customerContext = new InMemoryCustomerContext($this->eventLog, new TacticianFactory(), [
            new Product(new SKU('SKU_1'), Price::EUR(2500), true),
            new Product(new SKU('SKU_2'), Price::EUR(2500), true)
        ]);
    }

    public function clear()
    {
    }
}