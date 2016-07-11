<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Domain\Exception;

class InvalidArgumentException extends Exception
{
    /**
     * @param int $given
     *
     * @return InvalidArgumentException
     */
    public static function negativePriceAmount(int $given) : InvalidArgumentException
    {
        return new self(sprintf("Price amount can't be lower than 0, \"%d\" given", $given));
    }

    /**
     * @param int $given
     *
     * @return InvalidArgumentException
     */
    public static function negativePricePrecision(int $given) : InvalidArgumentException
    {
        return new self(sprintf("Price precision can't be lower than 0, \"%d\" given", $given));
    }

    /**
     * @return InvalidArgumentException
     */
    public static function emptySku() : InvalidArgumentException
    {
        return new self("Product SKU code can't be empty");
    }

    /**
     * @param string $currency
     *
     * @return InvalidArgumentException
     */
    public static function invalidCurrency(string $currency) : InvalidArgumentException
    {
        return new self(sprintf('Invalid currency code "%s"', $currency));
    }
}
