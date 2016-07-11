<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\Domain;

use Dumplie\SharedKernel\Domain\Money\Price;
use Dumplie\SharedKernel\Domain\Product\SKU;

final class Product
{
    /**
     * @var SKU
     */
    private $sku;

    /**
     * @var Price
     */
    private $price;

    /**
     * @var bool
     */
    private $isInStock;

    /**
     * Product constructor.
     *
     * @param SKU   $sku
     * @param Price $price
     * @param bool  $isInStock
     */
    public function __construct(
        SKU $sku,
        Price $price,
        bool $isInStock
    ) {
        $this->sku = $sku;
        $this->price = $price;
        $this->isInStock = $isInStock;
    }

    public function removeFromStock()
    {
        $this->isInStock = false;
    }

    public function putBackToStock()
    {
        $this->isInStock = true;
    }

    /**
     * @return SKU
     */
    public function sku(): SKU
    {
        return $this->sku;
    }
}
