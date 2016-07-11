<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Domain\Exception;

use Dumplie\SharedKernel\Domain\Product\SKU;

class ProductNotFoundException extends NotFoundException
{
    /**
     * @param SKU $sku
     *
     * @return ProductNotFoundException
     */
    public static function bySku(SKU $sku)
    {
        return new self(sprintf('Product with SKU "%s" does not exists.', (string) $sku));
    }
}
