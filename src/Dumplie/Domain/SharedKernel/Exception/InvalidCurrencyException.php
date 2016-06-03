<?php

declare (strict_types = 1);

namespace Dumplie\Domain\SharedKernel\Exception;

class InvalidCurrencyException extends InvalidArgumentException
{
    /**
     * InvalidCurrencyException constructor.
     *
     * @param string $baseCurrency
     * @param string $addedCurrency
     */
    public function __construct(string $baseCurrency, string $addedCurrency)
    {
        parent::__construct(sprintf("Can't add price with \"%s\" currency to \"%s\"", $baseCurrency, $addedCurrency));
    }
}
