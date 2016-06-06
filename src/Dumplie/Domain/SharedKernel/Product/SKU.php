<?php

declare (strict_types = 1);

namespace Dumplie\Domain\SharedKernel\Product;

use Dumplie\Domain\SharedKernel\Exception\InvalidArgumentException;

final class SKU
{
    /**
     * @var string
     */
    private $code;

    /**
     * @param string $code
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $code)
    {
        if (empty($code)) {
            throw InvalidArgumentException::emptySku();
        }

        $this->code = $code;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->code;
    }
}
