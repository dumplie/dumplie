<?php

declare (strict_types = 1);

namespace Dumplie\Test\Context;

use Dumplie\Domain\Inventory\Products;

interface InventoryContext extends Context
{
    /**
     * @return Products
     */
    public function products() : Products;

    /**
     * @param string $sku
     * @param int $price
     * @param string $currency
     * @param bool $available
     */
    public function addProduct(string $sku, int $price, string $currency = "EUR", bool $available = true);
}