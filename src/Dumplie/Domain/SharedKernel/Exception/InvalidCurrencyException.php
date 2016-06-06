<?php

declare (strict_types = 1);

namespace Dumplie\Domain\SharedKernel\Exception;

class InvalidCurrencyException extends InvalidArgumentException
{
    /**
     * @param string $expected
     * @param string $received
     */
    public function __construct(string $expected, string $received)
    {
        parent::__construct(sprintf("Can't add price with \"%s\" currency to \"%s\"", $expected, $received));
    }
}
