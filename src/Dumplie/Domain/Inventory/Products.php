<?php

declare (strict_types = 1);

namespace Dumplie\Domain\Inventory;

use Dumplie\Domain\Inventory\Exception\ProductNotFound;
use Dumplie\Domain\SharedKernel\Product\SKU;

interface Products
{
    /**
     * @param SKU $SKU
     *
     * @return Product
     *
     * @throws ProductNotFound - when product with given SKU were not found
     */
    public function getBySku(SKU $SKU): Product;

    /**
     * @param Product $product
     */
    public function add(Product $product);
}
