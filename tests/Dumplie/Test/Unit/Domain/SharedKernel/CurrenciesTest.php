<?php

namespace Dumplie\Test\Unit\Domain\SharedKernel;

use Dumplie\Domain\SharedKernel\Money\Currencies;

class CurrenciesTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    function it_validates_currency_code()
    {
        $this->assertTrue(Currencies::isValid("PLN"));
        $this->assertFalse(Currencies::isValid("ASR"));
    }

    /** @test */
    function it_ignores_lowercase_in_currency_code_validation()
    {
        $this->assertTrue(Currencies::isValid("pln"));
    }
}
