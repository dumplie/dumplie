<?php

declare (strict_types = 1);

namespace Dumplie\Domain\Customer;

use Dumplie\Domain\Customer\Exception\CartNotFoundException;

interface Carts
{
    /**
     * @param CartId $cartId
     *
     * @throws CartNotFoundException
     *
     * @return Cart
     */
    public function getById(CartId $cartId) : Cart;

    /**
     * @param Cart $cart
     */
    public function add(Cart $cart);
}
