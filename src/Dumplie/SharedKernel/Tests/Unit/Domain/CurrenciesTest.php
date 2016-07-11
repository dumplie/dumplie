<?php

namespace Dumplie\SharedKernel\Tests\Unit\Domain;

use Dumplie\SharedKernel\Domain\Money\Currencies;

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
