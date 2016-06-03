<?php

declare (strict_types = 1);

namespace Dumplie\Domain\Customer;

use Dumplie\Domain\Customer\Exception\ProductNotFoundException;
use Dumplie\Domain\SharedKernel\Product\SKU;

interface Products
{
    /**
     * @param SKU $sku
     *
     * @throws ProductNotFoundException
     *
     * @return Product
     */
    public function getBySku(SKU $sku) : Product;
}
