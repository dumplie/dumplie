<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Domain\Exception;

use Dumplie\Customer\Domain\Product;

class ProductNotAvailableException extends Exception
{
    /**
     * ProductNotAvailableException constructor.
     *
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        parent::__construct(sprintf('Product with SKU "%s" is not available.', $product->sku()));
    }
}
