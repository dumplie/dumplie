<?php

namespace Spec\Dumplie\Domain\Inventory;

use Dumplie\Domain\Inventory\Product;
use Dumplie\Domain\SharedKernel\Exception\InvalidArgumentException;
use Dumplie\Domain\SharedKernel\Money\Price;
use Dumplie\Domain\SharedKernel\Product\SKU;
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

    function it_is_initializable()
    {
        $this->shouldHaveType('Dumplie\Domain\Inventory\Product');
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
