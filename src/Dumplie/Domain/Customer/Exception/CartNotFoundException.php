<?php

declare (strict_types = 1);

namespace Dumplie\Domain\Customer\Exception;

use Dumplie\Domain\Customer\CartId;

class CartNotFoundException extends Exception
{
    /**
     * @param CartId $cartId
     *
     * @throws CartNotFoundException
     */
    public static function byId(CartId $cartId)
    {
        return new self(sprintf('Cart with id "%s" does not exists.', (string) $cartId));
    }
}
