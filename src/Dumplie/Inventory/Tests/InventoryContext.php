<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\Tests;

use Dumplie\Inventory\Domain\Products;
use Dumplie\SharedKernel\Tests\Context\Context;

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