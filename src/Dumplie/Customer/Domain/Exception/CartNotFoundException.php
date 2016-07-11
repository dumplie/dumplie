<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Domain\Exception;

use Dumplie\Customer\Domain\CartId;

class CartNotFoundException extends NotFoundException
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
