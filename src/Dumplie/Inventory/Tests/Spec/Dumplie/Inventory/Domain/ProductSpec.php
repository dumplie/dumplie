<?php

namespace Spec\Dumplie\Inventory\Domain;

use Dumplie\Inventory\Domain\Product;
use Dumplie\SharedKernel\Domain\Money\Price;
use Dumplie\SharedKernel\Domain\Product\SKU;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProductSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(
            new SKU("DUMPLIE_SKU_1"),
            Price::fromInt(250, 'PLN'),
            $isInStock = true
        );
    }

    function it_can_be_removed_from_stock()
    {
        $this->removeFromStock();

        $this->shouldBeLike(new Product(
            new SKU("DUMPLIE_SKU_1"),
            Price::fromInt(250, 'PLN'),
            $isInStock = false
        ));
    }

    function it_can_be_puting_back_to_stock()
    {
        $this->removeFromStock();
        $this->putBackToStock();

        $this->shouldBeLike(new Product(
            new SKU("DUMPLIE_SKU_1"),
            Price::fromInt(250, 'PLN'),
            $isInStock = true
        ));
    }

    function it_has_sku()
    {
        $this->sku()->shouldBeLike(new SKU("DUMPLIE_SKU_1"));
    }
}
