<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Application\Exception;

class QueryException extends Exception
{
    /**
     * @param $cartId
     *
     * @return QueryException
     */
    public static function cartNotFound($cartId) : QueryException
    {
        return new self(sprintf("Cart with id \"%s\" does not exists.", $cartId));
    }

    /**
     * @param $sku
     *
     * @return QueryException
     */
    public static function cartItemNotFound($sku) : QueryException
    {
        return new self(sprintf("Cart item with SKY \"%s\" does not exists.", $sku));
    }
}
