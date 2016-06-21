<?php

namespace Dumplie\Test\Unit\Domain\SharedKernel;

use Dumplie\Domain\SharedKernel\Money\Currencies;

class CurrenciesTest extends \PHPUnit_Framework_TestCase
{
    function test_validating_currency_code()
    {
        $this->assertTrue(Currencies::isValid("PLN"));
        $this->assertFalse(Currencies::isValid("ASR"));
    }

    function test_ignoring_lowercase_in_currency_code_validation()
    {
        $this->assertTrue(Currencies::isValid("pln"));
    }
}
