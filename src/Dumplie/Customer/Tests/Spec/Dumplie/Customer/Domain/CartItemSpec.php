<?php

namespace Spec\Dumplie\Customer\Domain;

use Dumplie\Customer\Domain\Exception\InvalidArgumentException;
use Dumplie\Customer\Domain\Product;
use Dumplie\SharedKernel\Domain\Money\Price;
use Dumplie\SharedKernel\Domain\Product\SKU;
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
