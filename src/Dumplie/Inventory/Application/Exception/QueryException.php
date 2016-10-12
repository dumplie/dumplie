<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\Application\Exception;

class QueryException extends Exception
{
    /**
     * @param $sku
     * @return QueryException
     */
    public static function productNotFound($sku) : QueryException
    {
        return new self(sprintf("Product with SKU \"%s\" does not exists.", $sku));
    }
}