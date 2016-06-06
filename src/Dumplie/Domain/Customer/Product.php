<?php

declare (strict_types = 1);

namespace Dumplie\Domain\Customer;

use Dumplie\Domain\Customer\Exception\InvalidArgumentException;
use Dumplie\Domain\SharedKernel\Money\Price;
use Dumplie\Domain\SharedKernel\Product\SKU;

final class Product
{
    /**
     * @var string
     */
    private $sku;

    /**
     * @var Price
     */
    private $price;

    /**
     * @var bool
     */
    private $available;

    /**
     * @param SKU   $sku
     * @param Price $price
     * @param bool  $available
     *
     * @throws InvalidArgumentException
     */
    public function __construct(SKU $sku, Price $price, bool $available)
    {
        $this->sku = $sku;
        $this->price = $price;
        $this->available = $available;
    }

    /**
     * @return SKU
     */
    public function sku() : SKU
    {
        return $this->sku;
    }

    /**
     * @return Price
     */
    public function price() : Price
    {
        return $this->price;
    }

    /**
     * @return bool
     */
    public function isAvailable() : bool
    {
        return $this->available;
    }
}
