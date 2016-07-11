<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Domain;

use Dumplie\Customer\Domain\Exception\ProductNotFoundException;
use Dumplie\SharedKernel\Domain\Product\SKU;

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
