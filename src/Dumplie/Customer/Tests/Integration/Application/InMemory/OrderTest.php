<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Tests\Integration\Application\InMemory;

use Dumplie\Customer\Domain\Product;
use Dumplie\SharedKernel\Domain\Money\Price;
use Dumplie\SharedKernel\Domain\Product\SKU;
use Dumplie\SharedKernel\Infrastructure\InMemory\InMemoryEventLog;
use Dumplie\SharedKernel\Infrastructure\InMemory\Transaction\Factory;
use Dumplie\Customer\Tests\Integration\Application\Generic\OrderTestCase;
use Dumplie\Customer\Tests\InMemory\InMemoryCustomerContext;
use Dumplie\SharedKernel\Tests\Context\Tactician\TacticianFactory;

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