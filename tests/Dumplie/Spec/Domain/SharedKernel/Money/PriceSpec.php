<?php

namespace Spec\Dumplie\Domain\SharedKernel\Money;

use Dumplie\Domain\SharedKernel\Exception\DifferentPricePrecisionException;
use Dumplie\Domain\SharedKernel\Exception\InvalidArgumentException;
use Dumplie\Domain\SharedKernel\Exception\InvalidCurrencyException;
use Dumplie\Domain\SharedKernel\Money\Price;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PriceSpec extends ObjectBehavior
{
    function it_throws_exception_when_value_is_negative()
    {
        $this->shouldThrow(InvalidArgumentException::class)->during('__construct', [-100, 'EUR']);
    }

    function it_throws_exception_when_precision_is_negaive()
    {
        $this->shouldThrow(InvalidArgumentException::class)->during('__construct', [1000, 'EUR', -100]);
    }

    function it_is_converted_to_float()
    {
        $this->beConstructedWith(10000, 'EUR', 100);

        $this->floatValue()->shouldReturn(100.00);
    }

    function it_can_be_created_from_integer()
    {
        $this->beConstructedThrough('fromInt', [100, 'EUR']);

        $this->floatValue()->shouldReturn(100.00);
    }

    function it_knows_when_other_price_has_same_currency()
    {
        $this->beConstructedThrough('fromInt', [100, 'EUR']);
        $this->hasSameCurrency(Price::EUR(100))->shouldReturn(true);
        $this->hasSameCurrency(Price::PLN(100))->shouldReturn(false);
    }

    function it_throws_exception_when_adding_to_price_with_a_different_currency()
    {
        $this->beConstructedThrough('fromInt', [100, 'EUR']);

        $this->shouldThrow(InvalidCurrencyException::class)->during('add', [Price::PLN(100)]);
    }

    function it_throws_exception_when_adding_to_price_with_a_different_precisions()
    {
        $this->beConstructedWith(10000, 'EUR', 100);

        $this->shouldThrow(DifferentPricePrecisionException::class)->during('add', [Price::EUR(100, 10)]);
    }

    function it_can_be_multiplied()
    {
        $this->beConstructedWith(10000, 'EUR', 100);

        $this->multiply(5)->floatValue()->shouldReturn(500.00);
    }
}
