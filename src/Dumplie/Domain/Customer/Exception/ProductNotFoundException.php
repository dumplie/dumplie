<?php

declare (strict_types = 1);

namespace Dumplie\Domain\Customer\Exception;

class ProductNotFoundException extends NotFoundException
{
    /**
     * @param string $sku
     *
     * @return ProductNotFoundException
     */
    public static function bySku(string $sku)
    {
        return new self(sprintf('Product with SKU "%s" does not exists.', $sku));
    }
}
