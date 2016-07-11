<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\Domain\Exception;

use Dumplie\SharedKernel\Domain\Exception\Exception;
use Dumplie\SharedKernel\Domain\Product\SKU;

final class ProductNotFound extends Exception
{
    /**
     * @param SKU $sku
     *
     * @return ProductNotFound
     */
    public static function bySku(SKU $sku): ProductNotFound
    {
        return new self(
            sprintf('Product with SKU "%s" does not found in inventory', $sku)
        );
    }
}
