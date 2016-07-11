<?php

namespace Dumplie\SharedKernel\Tests\Unit\Domain;

use Dumplie\SharedKernel\Domain\Money\Price;

class PriceTest extends \PHPUnit_Framework_TestCase
{
    function test_if_can_be_created_through_static_constructor()
    {
        $price = Price::EUR(10000);

        $this->assertEquals(100.00, $price->floatValue());
        $this->assertEquals('EUR', $price->currency());
    }
}
