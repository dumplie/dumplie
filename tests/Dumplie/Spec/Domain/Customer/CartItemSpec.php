<?php

namespace Spec\Dumplie\Domain\Customer;

use Dumplie\Domain\Customer\Exception\InvalidArgumentException;
use Dumplie\Domain\Customer\Product;
use Dumplie\Domain\SharedKernel\Money\Price;
use Dumplie\Domain\SharedKernel\Product\SKU;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CartItemSpec extends ObjectBehavior
{
    function it_throws_exception_when_unit_is_lower_than_1()
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('__construct', [new SKU("DUMPLIE_SKU_1"), 0]);
    }
}
