<?php

declare (strict_types = 1);

namespace Dumplie\Domain\Customer\Exception;

class InvalidArgumentException extends Exception
{
    /**
     * @param string $currency
     *
     * @return InvalidArgumentException
     */
    public static function invalidCurrency(string $currency) : InvalidArgumentException
    {
        return new self(sprintf('Invalid currency code "%s"', $currency));
    }

    /**
     * @param string $value
     * @param string $valueName
     * 
     * @return InvalidArgumentException
     */
    public static function invalidAddress(string $value, string $valueName) : InvalidArgumentException
    {
        return new self(sprintf('"%s" is not valid "%s"', $value, $valueName));
    }
}
