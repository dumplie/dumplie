<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Domain;

use Dumplie\Customer\Domain\Exception\CartNotFoundException;

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

    /**
     * @param CartId $cartId
     *
     * @throws CartNotFoundException
     */
    public function remove(CartId $cartId);

    /**
     * @param CartId $cartId
     * @return bool
     */
    public function exists(CartId $cartId) : bool;
}
