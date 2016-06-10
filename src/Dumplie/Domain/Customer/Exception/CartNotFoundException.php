<?php

declare (strict_types = 1);

namespace Dumplie\Domain\Customer\Exception;

use Dumplie\Domain\Customer\CartId;

class CartNotFoundException extends Exception
{
    /**
     * @param CartId $cartId
     *
     * @return CartNotFoundException
     */
    public static function byId(CartId $cartId): CartNotFoundException
    {
        return new self(sprintf('Cart with id "%s" does not exists.', (string) $cartId));
    }
}
